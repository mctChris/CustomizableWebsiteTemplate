<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\MediaFolder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use ImageOptimizer;
class Media extends BaseModel
{
    protected $table = 'media';
    protected $resizedBaseFolder = 'resized';
    protected static $storage_base_path = 'app/public';
    public $thumbnail_width = 140;
    protected $appends = ['thumbnail', 'path'];

    public function getPathAttribute()
    {
        return Storage::url($this->getPath());
    }

    public function getThumbnailAttribute()
    {
        if($this->isImage()){
            return $this->getResizedImage();
        }else if(preg_match("/(svg|webp)/", $this->mime_type)){
            return Storage::url($this->getPath());
        }

        return null;
    }

    public function getPath(){
        $folder_path = '';
        if($this->parent){
            $folder_path = $this->parent->getPath();
        }
        return $folder_path . '/' . $this->file_name;
    }

    public function parent()
    {
        return $this->hasOne('App\MediaFolder', 'id', 'folder_id');
    }

    public function post_medias()
    {
        return $this->hasMany('App\PostMedia', 'media_id', 'id');
    }

    public function isImage()
    {
        return preg_match("/(jpg|jpeg|png|gif)/", $this->mime_type);
    }

    public function getResizedImage($width = null, $height = null, $force_resize = false, $fill_bg = false, $anchor = 'center', $convert_to_png = false)
    {
        if(!$this->isImage()){
            return $this->path;
        }

        if(!$width && !$height){
            $width = $this->thumbnail_width;
        }

        $resizedFolderPath = $this->resizedBaseFolder . '/' . $this->id . '/' . $width . 'x' . $height . ($fill_bg ? 'bg' : '') . ($anchor != 'center' ? $anchor : '');
        $resizedPath = $resizedFolderPath . '/' . $this->file_name;
        
        if($width && $height && $fill_bg && $convert_to_png){

            $resizedPath = preg_replace("/\.(jpg|jpeg|gif)$/", ".png", $resizedPath);
        }

        if(str_replace("www.", '', config('app.url')) == str_replace("www.", '', url('')) && !Storage::exists($resizedPath)){
            Storage::makeDirectory($resizedFolderPath);
            $img_path =  storage_path(Media::$storage_base_path . '/' . $this->getPath());
            $savePath = storage_path(Media::$storage_base_path . '/' . $resizedPath);

            if(file_exists($img_path)){
                $img = Image::make($img_path);

                if(!$force_resize){
                    if(($img->width() < $width && $height === null) || ($img->height() < $height && $width === null)){
                        $img->save($savePath);
                        return Storage::url($resizedPath);
                    }
                }

                if($width && $height){
                    if(!$fill_bg){
                        $img->fit($width, $height);
                    }else{
                        if(!preg_match("/png/", $img->mime()) && $convert_to_png){
                            $img = $img->encode('png');
                        }
                        $img->resize($width, $height, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $img->resizeCanvas($width, $height, $anchor);
                    }
                }else{
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $img->save($savePath);
            }
        }

        return Storage::url($resizedPath);
    }

    public static function saveMedia($data, $file){
        $media = new Media;
        $media->folder_id = isset($data['folder_id']) ? $data['folder_id'] : 0;
        $orgin_folder_id = $media->folder_id;
        $media->extension = $file->getClientOriginalExtension();
        $media->title = preg_replace("/\." . $media->extension . "$/", '', $file->getClientOriginalName());
        $media->file_name = $file->getClientOriginalName();
        $media->disk = config('filesystems.default');
        $media->size = $file->getSize();
        $media->mime_type = $file->getMimeType();
        $media->created_by = auth()->user()->id;
        $media->updated_by = auth()->user()->id;

        $file_full_path = isset($data['fullPath']) ? $data['fullPath'] : $media->file_name;

        $file_regex = preg_quote($media->file_name, '/');
        $folder_path =  preg_replace("/\/?" . $file_regex . "$/", '', $file_full_path);

        $mediaFolder = MediaFolder::createRecursive($media->folder_id, $folder_path);
        $media->folder_id = $mediaFolder->id;
        $media->renameIfNeed();

        if(Storage::put($mediaFolder->getPath() . '/' . $media->file_name, file_get_contents($file))){

            // perform some process for image
            if($media->isImage()){
                $img_path = storage_path(Media::$storage_base_path . '/' . $media->getPath());
                $image = Image::make($img_path);

                // resize image size if enabled max size
                $systemSetting = SystemSetting::first()->details;

                if(isset($systemSetting['enable_max_img_size']) && $systemSetting['enable_max_img_size']){

                    $image_width = $image->width();
                    $image_height = $image->height();

                    if($image_width > $systemSetting['img_max_width'] || $image_height > $systemSetting['img_max_height']){

                        if($image_width > $systemSetting['img_max_width']){
                            $image->resize($systemSetting['img_max_width'], null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }

                        $image_height = $image->height();

                        if($image_height > $systemSetting['img_max_height']){
                            $image->resize(null, $systemSetting['img_max_height'], function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }

                        $image->save($img_path);

                    }

                }
                
                // add watermark if need
                if(config('appcustom.watermark') && isset($data['watermark']) && $data['watermark'] === 'true'){
                    $watermark = Image::make(storage_path(Media::$storage_base_path . '/' . config('appcustom.watermark_path')));
                    $watermark_offset = (int)($image->height() * 0.23);
                    $watermark_width = (int)($image->width() * 0.3);

                    $watermark->resize($watermark_width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $image->insert($watermark, 'bottom', 0, $watermark_offset);
                    $image->save($img_path);
                }

                // set jpg quality
                if(isset($systemSetting['jpg_quality']) && $systemSetting['jpg_quality']){
                    config(['image-optimizer.optimizers.' . \Spatie\ImageOptimizer\Optimizers\Jpegoptim::class . '.0' => '-m' . $systemSetting['jpg_quality']]);
                }
                
                ImageOptimizer::optimize($img_path);
                clearstatcache();
                $media->size = $image->filesize();

            }

            $media->save();
            $media->path = Storage::url($media->getPath());

            return [
                'orgin_folder_id' => $orgin_folder_id,
                'media' => $media,
            ];
        }


        return false;
    }

    public function delete()
    {
        Storage::delete($this->getPath());
        Storage::deleteDirectory($this->resizedBaseFolder . '/' . $this->id);
        parent::delete();
        return true;
    }
    public static function deleteBy($id)
    {
        $media = Media::find($id);
        if($media){

            if($media->post_medias){
                foreach ($media->post_medias as $post_media) {
                    $post_media->delete();
                }
            }

            return $media->delete();
        }
        return false;
    }
    public function move($folder_id)
    {
        if($this->folder_id == $folder_id){
            return false;
        }
        $old_path = $this->getPath();
        $this->folder_id = $folder_id;
        $this->renameIfNeed();
        $this->save();
        $newMedia = Media::find($this->id);
        $new_path = $newMedia->getPath();
        Storage::move($old_path, $new_path);
        return $this;
    }
    public static function moveBy($id, $folder_id)
    {
        $media = Media::find($id);
        if($media){
            return $media->move($folder_id);
        }
        return false;
    }
    public function renameIfNeed($folder_id = false){
        if(!$folder_id){
            $folder_id = $this->folder_id;
        }
        $i = 1;
        while (Media::where(['folder_id' => $folder_id, 'file_name' => $this->file_name])->first()){
            $this->file_name = sprintf("%s-%d.%s", $this->title, ++ $i, $this->extension);
        }
    }

    public static function editBy($id, $data){
        $media = Media::find($id);
        if($media){
            return $media->edit($data);
        }
        return false;
    }
    public function edit($data){
        $this->title = $data['title'];
        $this->save();
        if(isset($data['image'])){
            Storage::deleteDirectory( $this->resizedBaseFolder . '/' . $this->id);
            $img = Image::make($data['image']);
            $img_path =  storage_path(Media::$storage_base_path . '/' . $this->getPath());
            $img->save($img_path);
            $this->getResizedImage();
        }
        return $this;
    }
}

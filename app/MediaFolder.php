<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Media; 
class MediaFolder extends BaseModel
{
    protected $table = 'media_folder';
    protected $base_folder = 'media';
    public function getPath(){
        $folder_array = [];
        $folder_array[] = $this->name;
        $parent = $this->parent;
        while($parent){
            $folder_array[] = $parent->name;
            $parent = $parent->parent;
        }
        $folder_array[] = $this->getBaseFolder();
        return implode('/', array_reverse($folder_array));
    }

    public function getBaseFolder(){
        return $this->base_folder;
    }

    public function parent()
    {
        return $this->hasOne($this->getModel(), 'id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany($this->getModel(), 'parent_id', 'id');
    }

    public function medias()
    {
        return $this->hasMany('App\Media', 'folder_id', 'id');
    }

    public static function folderTree($parent_id = 0)
    {
        $records = self::where('parent_id', $parent_id)->get();
        foreach ($records as $record){
            $record->children = self::folderTree($record->id);
        }
        return $records;
    }

    public static function createRecursive($parent_folder_id = 0, $folder_path){

        $folder_array = explode('/', $folder_path);

        if(empty($folder_array) || empty($folder_array[0])){
            return MediaFolder::find($parent_folder_id);
        }else{
            foreach ($folder_array as $folder_name) {
                $mediaFolder = self::create($parent_folder_id, $folder_name);
                $parent_folder_id = $mediaFolder->id;
            }
        }

        return $mediaFolder;

    }

    public static function create($parent_folder_id = 0, $folder_name){
        $is_exists_folder = MediaFolder::where(['parent_id' => $parent_folder_id, 'name' => $folder_name])->first();
        if(!$is_exists_folder){
            $parentFolder = MediaFolder::find($parent_folder_id);
            if(!$parentFolder){
                $parentFolder = new MediaFolder;
                $parentFolder->id = 0;
            }

            $new_folder_path = $parentFolder->getPath() . '/' . $folder_name;

            if(!Storage::exists($new_folder_path)){
                Storage::makeDirectory($new_folder_path);
            }
            $mediaFolder = new MediaFolder;
            $mediaFolder->name = $folder_name;
            $mediaFolder->parent_id = $parent_folder_id;
            $mediaFolder->created_by = Auth::id();
            $mediaFolder->updated_by = Auth::id();
            $mediaFolder->save();
            $mediaFolder->is_new = true;
        }else{
            $mediaFolder = $is_exists_folder;
            $mediaFolder->is_new = false;
        }

        return $mediaFolder;
    }
    
    public static function deleteBy($folder_id){
        $mediaFolder = MediaFolder::find($folder_id);
        if($mediaFolder){
            return $mediaFolder->delete();
        }
        return false;
    }
    public function delete(){
        $is_exists_child_folder = MediaFolder::where(['parent_id' => $this->id])->first();
        $is_exists_media_in_folder = Media::where(['folder_id' => $this->id])->first();
        if(!($is_exists_child_folder || $is_exists_media_in_folder)){
            Storage::deleteDirectory($this->getPath());
            parent::delete();
            return true;
        }
        return false;
    }
    public static function moveBy($folder_id, $destination_folder_id){
        $mediaFolder = MediaFolder::find($folder_id);
        if($mediaFolder){
            return $mediaFolder->move($destination_folder_id);
        }
        return false;
    }

    public function move($destination_folder_id){
        $old_path = $this->getPath();
        $this->renameIfNeed($destination_folder_id);
        $this->parent_id = $destination_folder_id;
        $this->save();
        $newFolder = MediaFolder::find($this->id);
        $new_path = $newFolder->getPath();
        Storage::move($old_path, $new_path);
        return $newFolder;
    }

    public function renameIfNeed($folder_id){
        // if($folder_id == 0){

        // }
        $name = $this->name;
        $i = 1;
        while (parent::where('parent_id', $folder_id)->where('name', $this->name)->first()){
            $this->name = sprintf("%s-%d", $name, ++ $i);
        }
    }


    public static function renameBy($folder_id, $folder_name){        
        $mediaFolder = MediaFolder::find($folder_id);
        if($mediaFolder){
            return $mediaFolder->rename($folder_name);
        }
        return $mediaFolder;
    }

    public function rename($folder_name){        
        $is_exists_folder = MediaFolder::where(['parent_id' => $this->parent_id, 'name' => $folder_name])->first();
        if(!$is_exists_folder){
            $old_path = $this->getPath();
            $this->name = $folder_name;
            $this->save();
            $new_path = $this->getPath();
            Storage::move($old_path, $new_path);
        }else{
            return false;
        }
        
        return $this;
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class PostMedia extends BaseModel
{
    protected $table = 'post_media';

    public function __construct()
    {
        parent::__construct();
    }
    public function medias(){
        return $this->hasMany('App\Media', 'id', 'media_id');
    }
    public static function saveWith($medias, $post_model){
        foreach ($medias as $language_id => $_medias) {
            foreach ($_medias as $type => $type_medias) {
                PostMedia::where([
                    'type' => $type,
                    'post_model' => $post_model->getModel(),
                    'post_id' => $post_model->id,
                    'language_id' => $language_id
                ])->delete();

                foreach ($type_medias as $index => $media_id) {
                    $arrange = count($type_medias) - $index;
                    if($media_id){
                        $postMedia = new PostMedia;
                        $postMedia->type = $type;
                        $postMedia->language_id = $language_id;
                        $postMedia->post_id = $post_model->id;
                        $postMedia->media_id = $media_id;
                        $postMedia->arrange = $arrange;
                        $postMedia->post_table = $post_model->getTable();
                        $postMedia->post_model = $post_model->getModel();
                        $postMedia->created_by = auth()->user()->id;
                        $postMedia->updated_by = auth()->user()->id;
                        $postMedia->save();
                    }
                }
            }
        }
    }
}
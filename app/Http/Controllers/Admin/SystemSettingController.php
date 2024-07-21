<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SystemSettingController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listing()
    {
        parent::listing();
        $data['config'] = $this->getConfig();
        $data['records'] = $this->getPosts();
        return view($this->section('listing'), $data);
    }
    
    public function detail($id = '')
    {
        parent::detail($id);
        $data['record'] = $this->getPost($id);
        $data['config'] = $this->getConfig();
        $data['languages'] = $this->getLanguages();
        $data['medias'] = $data['record']->getMedias();
        $data['id'] = $id;
        $data['default_language_list'] = [];

        foreach ($data['languages']->sortBy('created_at') as $key => $lang) {
            $data['default_language_list'][$key]['title'] = $lang['name'];
            $data['default_language_list'][$key]['value'] = $lang['id'];
            $data['default_language_list'][$key]['checked'] = $lang['default_language'];
        }

        return view($this->section('detail'), $data);
    }

    public function arrangement($id = '')
    {
        $data['config'] = $this->getConfig();
        $data['records'] = $this->getAllPosts();
        return view($this->section('arrangement'), $data);
    }

    public function postsQuery($route_params, $query){
        return $query;
    }

    public function beforeSave(Request $request, $customData = []){
        $languages = \App\Language::where('active', 1)->get();

        foreach ($languages as $lang) {
            if ($lang->id == $request->details['default_language']) {
                $lang['default_language'] = 1;
                $lang->save();
            } else {
                $lang['default_language'] = 0;
                $lang->save();
            }
        }

        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }

    public function afterDelete(Request $request, $model){

    }

}

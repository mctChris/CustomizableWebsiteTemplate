<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class {controller}CategoryController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listing($parent_id = 0)
    {
        parent::listing();
        $data['config'] = $this->getConfig();
        $data['records'] = $this->getPosts();
        return view($this->section('listing'), $data);
    }
    
    public function postsQuery($route_params, $query){
        $query = $query->where('parent_id', $route_params['parent_id'] ?? 0);
        return $query;
    }

    public function detail($id = '', $parent_id = 0)
    {
        parent::detail($id);
        $data['config'] = $this->getConfig();
        $data['languages'] = $this->getLanguages();
        $data['record'] = $this->getPost($id);
        $data['medias'] = $data['record']->getMedias();
        $selected_parent_id = $data['record']->parent_id ? $data['record']->parent_id : $parent_id;
        $data['table_tree_html'] = $this->tableTreeOptionHtml($selected_parent_id);
        $data['id'] = $id;
        $data['record']->initRepeater(true);
        return view($this->section('detail'), $data);
    }

    public function arrangement($parent_id = '')
    {
        $data['config'] = $this->getConfig();
        $data['records'] = $this->getAllPosts();
        return view($this->section('arrangement'), $data);
    }

    public function beforeSave(Request $request, $customData = []){
        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }

    public function afterDelete(Request $request, $model){

    }

}

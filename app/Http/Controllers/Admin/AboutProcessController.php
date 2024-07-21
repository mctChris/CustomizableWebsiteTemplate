<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AboutProcessController extends BaseAdminController
{

    protected $listingVisibleFieldsOnJson = ['id', 'title', 'process_heading'];    
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
        $data['record']->initRepeater(true);

        // dd($data['record']);

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
        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }

    public function afterDelete(Request $request, $model){

    }

    public function getPageName() {
        $pageName = \App\AboutProcessPage::withDescription()->first()->page_title;
        return $pageName;
    }        
    
}
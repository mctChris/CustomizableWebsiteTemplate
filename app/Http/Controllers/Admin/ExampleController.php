<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ExampleController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['config'] = $this->getConfig();
        $data['languages'] = $this->getLanguages();
        return view($this->section('index'), $data);
    }

    public function beforeSave(Request $request, $customData = []){
        Validator::make($request->all(), 
            ['languages.*.title' => 'required'], [], ['languages.*.title' => 'title']
        )->validate();
        return $customData;
    }
    
    public function afterSave(Request $request, $customData = [], $model){

    }

    public function afterDelete(Request $request, $model){

    }
}

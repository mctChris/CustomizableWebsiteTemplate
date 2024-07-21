<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Role;

const PERMISSIONS_TYPE = [
    'view', 'update', 'delete'
];

class RoleController extends BaseAdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function listing()
    {
        parent::listing();
        $data['records'] = $this->getPosts();
        $data['config'] = $this->getConfig();
        return view($this->section('listing'), $data);
    }

    public function detail($id = '')
    {
        parent::detail($id);
        $data['record'] = $this->getPost($id);
        $data['config'] = $this->getConfig();
        $data['id'] = $id;
        $data['permissions_type'] = PERMISSIONS_TYPE;
        $data['record']->permissions = json_decode($data['record']->permissions, true);

        return view($this->section('detail'), $data);
    }

    public function arrangement($id = '')
    {
        $data['records'] = $this->getAllPosts();
        $data['config'] = $this->getConfig();
        return view($this->section('arrangement'), $data);
    }

    public function postsQuery($route_params, $query){
        return $query;
    }

    public function beforeSave(Request $request, $customData = []){
        $permissions = $request->get('permissions');
        $customData['permissions'] = $permissions ? json_encode($permissions) : '';
        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }

    public function afterDelete(Request $request, $model){

    }

}

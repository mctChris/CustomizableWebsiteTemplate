<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseAdminController
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
        $data['config'] = $this->getConfig();
        $data['record'] = $this->getPost($id);
        $data['roles'] = \App\Role::all()->toArray();
        $data['id'] = $id;
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

        $validatorRules = [
            'name' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
            'role_id' => ['required']
        ];

        if($new_password = $request->input('new_password')){

            $validatorRules['new_password'] = ['min:8'];

            if(empty($request->get('id'))){
                //create user
                array_push($validatorRules['username'], 'unique:users,username');
                array_push($validatorRules['email'], 'unique:users,email');
                array_push($validatorRules['new_password'], 'required');
            }else{
                //update user password
                $currentPassword = $request->input('current_admin_password');
                $validatorRules['current_admin_password'] = [
                    'required',
                    function ($attribute, $value, $fail) {
                        $credentials = [
                            'email' =>  Auth::user()->email,
                            'password' => $value
                        ];
                        if(!Auth::attempt($credentials)){
                            $fail('Current admin password does not match.');
                        }
                    }
                ];
            }
            $customData['password'] = Hash::make($new_password);
        }

        Validator::make($request->all(), $validatorRules)->validate();

        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }

    public function afterDelete(Request $request, $model){

    }

}

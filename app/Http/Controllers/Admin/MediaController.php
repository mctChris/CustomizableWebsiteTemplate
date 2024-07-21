<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Route;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Media;
use App\MediaFolder;
class MediaController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function config(){
        $config = [];
        $all_folder = MediaFolder::folderTree();
        $dir_icon = "";
        
        return  [
            'dir' => [
                'icon' => $dir_icon,
                'list' => $all_folder->toArray()
            ],
            'endpoints' => [
                'upload' => route('admin.media.upload'),
                'medias_in_folder' => route('admin.media.medias_in_folder'),
                'create_folder' => route('admin.media.create_folder'),
                'rename_folder' => route('admin.media.rename_folder'),
                'delete_folder' => route('admin.media.delete_folder'),
                'move_folder' => route('admin.media.move_folder'),
                'move_media' => route('admin.media.move'),
                'edit_media' => route('admin.media.edit'),
                'delete_media' => route('admin.media.delete'),
            ]
        ];
    }


    public function upload(Request $request){
        $input_data = $request->all();

        $file = $input_data['file'];
        if($file->getError()){
            return response()->json([
                'status' => 'failure',
                'message' => $file->getErrorMessage()
            ]);
        }
        if($result = Media::saveMedia($input_data, $file));{
            return response()->json([
                'status' => 'success',
                'uploaded_file' => $result['media'],
                'folder_tree' => MediaFolder::folderTree($result['orgin_folder_id'])->toArray()
            ]);
        }

        return response()->json([
            'status' => 'failure'
        ]);      

    }

    public function medias_in_folder($folder_id){
        $medias = [];
        $mediaFolder = MediaFolder::find($folder_id);
        if($mediaFolder){
            request()->session()->put('lastBrowseFolderId', $mediaFolder->id);
            $medias = $mediaFolder->medias()->orderBy('file_name', 'asc')->get();

        }
        return response()->json([
            'status' => 'success',
            'medias' => $medias
        ]);
    }

    public function create_folder(Request $request){
        $input_data = $request->only(['parent_folder_id', 'new_folder_name']);
        $folder = MediaFolder::create($input_data['parent_folder_id'], $input_data['new_folder_name']);
        return response()->json([
            'status' => 'success',
            'folder' => $folder
        ]);
    }

    public function rename_folder(Request $request){
        $input_data = $request->only(['folder_id', 'new_folder_name']);
        $folder = MediaFolder::renameBy($input_data['folder_id'], $input_data['new_folder_name']);
        if($folder){
            return response()->json([
                'status' => 'success',
                'folder' => $folder
            ]);
        }
        return response()->json([
            'status' => 'failure',
        ]);
    }

    public function delete_folder(Request $request){
        $input_data = $request->only(['folder_id']);
        $success = MediaFolder::deleteBy($input_data['folder_id']);
        if($success){
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'failure',
            'message' => 'You cannot delete a folder when it still contain sub folder or files.'
        ]);
    }

    public function move_folder(Request $request){
        $input_data = $request->only(['folder_id', 'destination_folder_id']);
        $folder = MediaFolder::moveBy($input_data['folder_id'], $input_data['destination_folder_id']);
        if($folder){
            return response()->json([
                'status' => 'success',
                'folder' => $folder
            ]);
        }
        return response()->json([
            'status' => 'failure',
        ]);
    }

    public function move(Request $request){
        $input_data = $request->only(['id', 'folder_id']);
        if(isset($input_data['id']) && isset($input_data['folder_id'])){
            $ids = explode(",", $input_data['id']);
            foreach ($ids as $id) {
                Media::moveBy($id, $input_data['folder_id']);
            }
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'failure',
        ]);
    }

    public function edit(Request $request){
        $input_data = $request->all();
        if(isset($input_data['id'])){
            Media::editBy($input_data['id'], $input_data);
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'failure',
        ]);
    }

    public function delete(Request $request){
        $input_data = $request->only('id');
        if(isset($input_data['id'])){
            $ids = explode(",", $input_data['id']);
            foreach ($ids as $id) {
                Media::deleteBy($id);
            }
         
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'failure',
        ]);
    }

}

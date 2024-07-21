<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class MediaLibraryController extends BaseAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['config'] = $this->getConfig();
        return view($this->section('index'), $data);
    }
}

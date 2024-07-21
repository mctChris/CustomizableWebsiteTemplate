<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends BaseFrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        return parent::output(function($data){
            $data['page'] = \App\HomePage::withDescription()->first();
            $data['page']->initRepeater();

            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.index', $data);
        });
    }
}

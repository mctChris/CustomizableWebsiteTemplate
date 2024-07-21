<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends BaseFrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        return parent::output(function($data){
            
            $data['page'] = \App\ContactPage::withDescription()->first();
            $data['page']->initRepeater();

            // dd($data['page']->locations);

            $data['seo'] = $this->getSeo($data['page']);
            
            return view('frontend.contact', $data);
        });
    }        
}

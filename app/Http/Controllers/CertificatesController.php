<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificatesController extends BaseFrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        return parent::output(function($data){

            $data['page'] = \App\CertificatePage::withDescription()->first();
            
            $data['heading'] = \App\CertificatePage::withDescription()->pluck('heading')->first();

            $data['certificates'] = \App\Certificates::withDescription()->online()->arrange()->get();
            
            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.certificates', $data);
        });
    }        
}

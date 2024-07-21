<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends BaseFrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        return parent::output(function($data){

            $data['page'] = \App\NewsPage::withDescription()->first();
            
            $data['records'] = \App\News::withDescription()->online()->arrange()->get();

            foreach ($data['records'] as $record) {
                $record->overlay_color = color($record->overlay_color);
            }

            $data['seo'] = $this->getSeo($data['page']);
            
            return view('frontend.news', $data);
        });
    } 

    public function detail($news_url_slug = '') {

        return parent::output(function($data) use($news_url_slug){
            
            $data['news'] = \App\News::withDescription()->where('url_slug', $news_url_slug)->first();
            
            return view('frontend.news-details', $data);
        });
    }     
}

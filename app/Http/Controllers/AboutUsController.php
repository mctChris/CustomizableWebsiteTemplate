<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends BaseFrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function storyPage()
    {

        return parent::output(function($data){
            
            $data['page'] = \App\AboutUsPage::withDescription()->first();
            $data['page']->initRepeater();

            $data = $this->getSidebar($data);

            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.about-story', $data);
        });
    }

    public function valuePage()
    {

        return parent::output(function($data){
            
            $data['page'] = \App\AboutValuesPage::withDescription()->first();
            $data['page']->initRepeater();            

            $data = $this->getSidebar($data);

            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.about-values', $data);
        });
    }

    public function processPage()
    {

        return parent::output(function($data){

            $data['page'] = \App\AboutProcessPage::withDescription()->first();

            $data['records'] = \App\AboutProcess::withDescription()->online()->arrange()->get();

            foreach ($data['records'] as $record) {
                $record->initRepeater();
            }

            $data = $this->getSidebar($data);

            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.about-process', $data);
        });
    }
    public function getSidebar($data) {

        $data['sidebar']['heading'] = \App\Translation::withDescription()->where('string_key', 'about-us')->first()->description;
        $data['sidebar']['items']['about-story']['title'] =  \App\AboutUsPage::withDescription()->pluck('page_title')->first();
        $data['sidebar']['items']['about-story']['route'] = route('about.story', [], false);
        $data['sidebar']['items']['about-story']['title'] =  \App\AboutUsPage::withDescription()->pluck('page_title')->first();
        $data['sidebar']['items']['about-values']['route'] = route('about.values', [], false);
        $data['sidebar']['items']['about-values']['title'] =  \App\AboutValuesPage::withDescription()->pluck('page_title')->first();
        $data['sidebar']['items']['about-process']['route'] = route('about.process', [], false);
        $data['sidebar']['items']['about-process']['title'] =  \App\AboutProcessPage::withDescription()->pluck('page_title')->first();

        return $data;

    }       
}

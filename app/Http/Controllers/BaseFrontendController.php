<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseFrontendController extends Controller
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function output($callback){

        $data['current_language'] =  \App\Language::getCurrentLanguage();
        $data['all_languages'] = \App\Language::getAllLanguageRoutes();
        $data['other_languages'] = $data['all_languages']->reject(function($language) {
            return $language->code == \App::getLocale();
        });

        $data['header'] = \App\Header::withDescription()->first();
        $data['header']->initRepeater();

        $data['site_title'] = \App\SystemSetting::withDescription()->first();

        $data['page_title']['about'] = \App\Translation::withDescription()->where('string_key', 'about-us')->first()->description;
        $data['page_title']['about_story'] = \App\AboutUsPage::withDescription()->pluck('page_title')->first();
        $data['page_title']['about_values'] = \App\AboutValuesPage::withDescription()->pluck('page_title')->first();
        $data['page_title']['about_process'] = \App\AboutProcessPage::withDescription()->pluck('page_title')->first();
        $data['page_title']['products'] = \App\ProductsPage::withDescription()->pluck('page_title')->first();
        $data['page_title']['news'] = \App\NewsPage::withDescription()->pluck('page_title')->first();
        $data['page_title']['certificates'] = \App\CertificatePage::withDescription()->pluck('page_title')->first();
        $data['page_title']['contact'] = \App\ContactPage::withDescription()->pluck('page_title')->first();


        $data['product_categories'] = \App\ProductsCategory::withDescription()->online()->pluck('url_slug', 'title')->toArray();
      
        return $callback($data);
    }

    public function getSeo($models = [], $description = ''){
        return \App\Seo::getSeo($models, $description);
    }

}

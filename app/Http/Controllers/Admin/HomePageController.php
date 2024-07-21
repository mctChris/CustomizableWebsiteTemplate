<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomePageController extends BaseAdminController
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
        $data['record'] = $this->getPost($id);
        $data['config'] = $this->getConfig();
        $data['languages'] = $this->getLanguages();
        $data['medias'] = $data['record']->getMedias();
        $data['id'] = $id;
        $data['record']->initRepeater(true);


        // Gather data for page select
        $about_story_title = \App\AboutUsPage::withDescription()->pluck('page_title')->first() . ' (Page)';
        $about_values_title = \App\AboutValuesPage::withDescription()->pluck('page_title')->first() . ' (Page)';
        $about_process_title = \App\AboutProcessPage::withDescription()->pluck('page_title')->first() . ' (Page)';
        $category_listing_title = \App\ProductsPage::withDescription()->pluck('page_title')->first() . ' (Page)';
        $news_page_title = \App\NewsPage::withDescription()->pluck('page_title')->first() . ' (Page)';
        $certificate_page_title = \App\CertificatePage::withDescription()->pluck('page_title')->first() . ' (Page)';
        $contact_page_title = \App\ContactPage::withDescription()->pluck('page_title')->first() . ' (Page)';

        $data['routeSelect'][] = ['title' => 'Home (Page)', 'value' => route('index', [], false)];
        $data['routeSelect'][] = ['title' => $about_story_title, 'value' => route('about.story', [], false)];
        $data['routeSelect'][] = ['title' => $about_values_title, 'value' => route('about.values', [], false)];
        $data['routeSelect'][] = ['title' => $about_process_title, 'value' => route('about.process', [], false)];
        $data['routeSelect'][] = ['title' => $category_listing_title, 'value' => route('products', [], false)];
        $data['routeSelect'][] = ['title' => $news_page_title, 'value' => route('news', [], false)];
        $data['routeSelect'][] = ['title' => $certificate_page_title, 'value' => route('certificates', [], false)];
        $data['routeSelect'][] = ['title' => $contact_page_title, 'value' => route('contact', [], false)];

        $product_category_title = \App\ProductsCategory::withDescription()->online()->arrange()->select('title', 'url_slug')->get();

        foreach ($product_category_title as $cat) {
            $data['routeSelect'][] = ['title' => $cat->title. ' (Product Category)', 'value' => route('products', [], false) . '/' . $cat->url_slug];
        }

        $products_title = \App\Products::withDescription()->online()->arrange()->select('title', 'url_slug', 'parent_id')->get();

        foreach ($products_title as $prod) {
            $prod->parent_slug = \App\ProductsCategory::withDescription()->find($prod->parent_id)->url_slug;
            unset($prod->parent_id);
        }


        foreach ($products_title as $prod) {
            $data['routeSelect'][] = ['title' => $prod->title . ' (Products)', 'value' => route('products', [], false) . '/' . $prod->parent_slug . '/' . $prod->url_slug];
        }

        $news_title = \App\News::withDescription()->online()->arrange()->select('title', 'url_slug')->get();

        foreach ($news_title as $news) {
            $data['routeSelect'][] = ['title' => $news->title . ' (News)', 'value' => route('news', [], false) . '/' . $news->url_slug];
        }
        
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
        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }
    
    public function afterDelete(Request $request, $model){

    }
}

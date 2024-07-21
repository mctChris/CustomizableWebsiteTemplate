<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends BaseFrontendController
{

    public function __construct()
    {
        parent::__construct();
    }


    public function categoryListing()
    {

        return parent::output(function($data){

            $data['page'] = \App\ProductsPage::withDescription()->first();

            $data['product_categories'] = \App\ProductsCategory::withDescription()->online()->arrange()->get();

            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.category-listing', $data);
        });
    }

    public function singleCategory($category = '') {

        return parent::output(function($data) use ($category) {

            $data['all_product_categories_title'] = \App\ProductsCategory::withDescription()->pluck('title')->toArray();

            $data['page'] = \App\ProductsCategory::where('url_slug', $category)->first();

            $parent_id = \App\ProductsCategory::where('url_slug', $category)->first()->id;

            $data['product_category_title'] = \App\ProductsCategory::where('url_slug', $category)->first()->title;

            $data['product_category_slug'] = $category;

            $data['products'] = \App\Products::withDescription()->where('parent_id', $parent_id)->online()->arrange()->get();

            $data = $this->getProductSidebar($data);

            $data['seo'] = $this->getSeo($data['page']);

            return view('frontend.single-category', $data);
        });        
    }       

    public function productDetail($category = '', $product_url_slug = '') {

        return parent::output(function($data) use ($category, $product_url_slug) {

            $data['parent_route'] = route('products', [], false) . '/' . $category;

            $data['product'] = \App\Products::withDescription()->where('url_slug', $product_url_slug)->first();

            $data['seo'] = $this->getSeo($data['product']);

            return view('frontend.product-details', $data);
        });  
    }


    public function getProductSidebar($data) {

        $data['sidebar']['heading'] = \App\ProductsPage::withDescription()->pluck('page_title')->first();

        $product_categories = \App\ProductsCategory::withDescription()->online()->arrange()->get();

        foreach ($product_categories as $category) {
            $data['sidebar']['items'][$category->url_slug]['title'] = $category->title;
            $data['sidebar']['items'][$category->url_slug]['route'] = route('products.singleCategory', ['category' => $category->url_slug], false);
        }

        return $data;

    }     
}

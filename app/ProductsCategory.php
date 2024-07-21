<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductsCategory extends BaseCategoryModel
{
    protected $table = 'products_category';

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        // return route('products', $params, false);
        return false;
    }

}

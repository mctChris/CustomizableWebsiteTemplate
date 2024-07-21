<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HomePage extends BasePageModel
{
    protected $table = 'home_page';

    public $casts = [
    	'banners' => 'array'
    ];    

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

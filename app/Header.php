<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Header extends BasePageModel
{
    protected $table = 'header';

    public $casts = [
    	'social_media' => 'array'
    ];

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

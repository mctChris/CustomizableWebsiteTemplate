<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AboutValuesPage extends BasePageModel
{
    protected $table = 'about_values_page';

    public $casts = [
    	'company_values' => 'array'
    ];

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContactPage extends BasePageModel
{
    protected $table = 'contact_page';

    public $casts = [
    	'locations' => 'array'
    ];

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

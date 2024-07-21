<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AboutProcess extends BaseModel
{
    protected $table = 'about_process';


    public $casts = [
    	'processes' => 'array'
    ];
    
    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

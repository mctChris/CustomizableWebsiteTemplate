<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AboutProcessPage extends BasePageModel
{
    protected $table = 'about_process_page';

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

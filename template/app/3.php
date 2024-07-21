<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class {controller} extends BaseChildModel
{
    protected $table = '{class}';

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

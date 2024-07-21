<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CertificatePage extends BasePageModel
{
    protected $table = 'certificate_page';

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

}

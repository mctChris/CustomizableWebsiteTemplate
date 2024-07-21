<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BasePageModel extends BaseModel
{
    public $is_page = true;
}

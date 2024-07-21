<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SystemSetting extends BasePageModel
{
    protected $table = 'system_setting';
    protected $casts = [
        'details' => 'array'
    ];
}

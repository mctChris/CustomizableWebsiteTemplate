<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends BaseModel
{
    protected $table = 'role';
    protected $guarded = ['id', 'is_super_admin'];

}

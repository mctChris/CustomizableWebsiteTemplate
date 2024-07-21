<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Translation extends BaseModel
{
    protected $table = 'translation';

    public function getProgress(){
        $complete = 0;
        foreach ($this->descriptions as $description) {
            if($description->description){
                $complete += 1;
            }
        }
        return round($complete / $this->descriptions->count() * 100) . '%';
    }
}

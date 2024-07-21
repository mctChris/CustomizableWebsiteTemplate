<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseChildModel extends BaseModel
{
    public $is_child = true;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getCategoryModel(){
        return $this->getModel() . ucfirst($this->category_table_suffix);
    }

    public function getCategoryDescriptionModel(){
        return $this->getCategoryDescriptionModel() . ucfirst($this->description_table_suffix);
    }

    public function getCategoryTable(){
        return $this->getTable() . '_' . $this->category_table_suffix;
    }

    public function getCategoryDescriptionTable(){
        return $this->getCategoryTable() . '_' . $this->description_table_suffix;
    }

    public function parent()
    {
        return $this->hasOne($this->getCategoryModel(), 'id', 'parent_id');
    }
}

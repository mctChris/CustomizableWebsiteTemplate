<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseCategoryModel extends BaseModel
{
    public $is_category = true;
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    public function getChildModel(){
        return str_replace(ucfirst($this->category_table_suffix), '', $this->getModel());
    }

    public function child_cats()
    {
        return $this->hasMany($this->getModel(), 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany($this->getChildModel(), 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne($this->getModel(), 'id', 'parent_id');
    }

    public function child_count()
    {
        if($this->has_child_category){
            return $this->child_cats()->count();
        }

        return $this->childs()->count();
    }

    public static function tableTree($parent_id = 0, $order_by = 'arrange|desc')
    {
        
        list($order_by_field, $order_by_order) = explode("|", $order_by);
        $records = self::findPostBy('parent_id', $parent_id)->orderBy($order_by_field, $order_by_order)->get();
        foreach ($records as $record)
        {
            if ($record->has_child_category){
                $record->childs = self::tableTree($record->id, $order_by);
            }
        }
        return $records;
    }



}

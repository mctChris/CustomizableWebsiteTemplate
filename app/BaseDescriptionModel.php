<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BaseDescriptionModel extends Model
{
    protected $primaryKey = ['description_id', 'language_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function getInstance(){
        return new static;
    }
    
    public function getMainTable(){
        return str_replace('_description', '', $this->table);
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('description_id', '=', $this->getAttribute('description_id'))
            ->where('language_id', '=', $this->getAttribute('language_id'));
        return $query;
    }


    public function seo()
    {
        return $this->hasOne(Seo::class, 'post_id', 'description_id')->where(['post_table' => $this->getMainTable(), 'language_id' => $this->language_id]);
    }


    public static function saveModel($description_id, $language_id, $data)
    {
        $model = parent::firstOrNew([
            'description_id' => $description_id,
            'language_id' => $language_id
        ]);
        return $model->patch($data);

    }
    public function patch($data)
    {
       foreach ($data as $key => $value) {
           $this->$key = $value;
       }
       $this->save();
       return $this;
    }
    
    public function getKey()
    {
        $attributes = [];
        foreach ($this->getKeyName() as $key) {
            $attributes[$key] = $this->getAttribute($key);
        }

        return $attributes;
    }

    public function replicate(array $except = null)
    {
        $defaults = [
            'description_id',
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
        ];

        $attributes = Arr::except(
            $this->attributes, $except ? array_unique(array_merge($except, $defaults)) : $defaults
        );

        return tap(new static, function ($instance) use ($attributes) {
            $instance->setRawAttributes($attributes);

            $instance->setRelations($this->relations);

            $instance->fireModelEvent('replicating', false);
        });
    }

}

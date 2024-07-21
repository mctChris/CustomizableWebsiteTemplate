<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;

class BaseModel extends Model
{


    protected $guarded = ['id'];
    protected $table_prefix = '';
    protected $order_by_key = 'arrange';
    protected $order_by_key_type = 'int';

    public $is_page = false;
    public $is_category = false;
    public $is_child = false;
    public $has_slug = true;
    public $can_duplicate = false;
    public $category_table_suffix = 'category';
    public $description_table_suffix = 'description';
    public $controller_suffix = 'Controller';

    protected $appends = ['title'];

    protected $hidden = ['medias'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            if(isAdminRoute()){
                $table_columns = collect($query->getTableColumns());
                if($table_columns->search('created_by') && empty($query->created_by)){
                    $query->created_by = auth()->id();
                }
                if($table_columns->search('publish_at') && empty($query->publish_at)){
                    $query->publish_at = date('Y-m-d H:i:s');
                }
            }
        });

        static::saving(function ($query) {
            if(isAdminRoute()){
                $table_columns = collect($query->getTableColumns());
                if($table_columns->search('updated_by')){
                    $query->updated_by = auth()->id();
                }
                if($table_columns->search('updated_at')){
                    $query->updated_at = \Carbon\Carbon::now();
                }
            }
        });

        // static::saved(function ($model) {
        //     if(isAdminRoute()){
        //         $model->autoSaveAttritubes();
        //     }
        // });

    }

    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }

    public static function getInstance(){
        return new static;
    }

    public static function table(){
        $instance = new static;
        return $instance->getTable();
    }

    public function getTitleAttribute()
    {

        if(isset($this->attributes['title'])){
            return $this->attributes['title'];
        }

        if($this->hasDescriptionModel()){
            return $this->language->title ?? null;
        }

        return null;
    }

    public function getBreadcrumbTitle()
    {
        return $this->title ?? 'Detail';
    }

    public function getModelByTable($table){
        return '\App\\'. str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
    }

    public function getSection(){
        return $this->getTable();
    }

    public function getTableWithoutPrefix(){
        return preg_replace("/^" . $this->table_prefix . "_(.*)/", "$1", $this->getTable());
    }

    public static function descriptionTable(){
        $instance = new static;
        return $instance->getDescriptionTable();
    }

    public static function descriptionModel(){
        $instance = new static;
        return $instance->getDescriptionModel();
    }

    public function getControllerName(){
        return (new \ReflectionClass($this))->getShortName() . $this->controller_suffix;
    }

    public function getModel(){
        return '\\' . get_class($this);
    }

    public function getCategorySuffix(){
        return $this->category_table_suffix;
    }

    public function getDescriptionTable(){
        return $this->getTable() . '_' . $this->description_table_suffix;
    }

    public function getDescriptionModel(){
        return $this->getModel() . ucfirst($this->description_table_suffix);
    }

    public function hasDescriptionModel(){
        return class_exists($this->getDescriptionModel());
    }

    public function isCategory(){
        return $this->is_category;
    }

    public function isChild(){
        return $this->is_child;
    }

    public function getOrderByKey(){
        return $this->order_by_key;
    }

    public function getOrderByKeyType(){
        return $this->order_by_key_type;
    }

    public function scopeFromLatest($query){
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeArrange($query){
        return $query->orderBy('arrange', 'desc');
    }

    public function scopeWithDescription($query, $default_language_id = true)
    {

        if($default_language_id === true){
            $language_id = current_lang()->id;
        }else{
            $language_id = $default_language_id;
        }

        $query->select($this->getTable() . '.*', $this->getDescriptionTable() . '.*')
            ->leftjoin($this->getDescriptionTable(), function ($join) use ($language_id) {
                $join->on($this->getTable() . '.id', '=', $this->getDescriptionTable() . '.description_id');
                if($language_id){
                    $join->where($this->getDescriptionTable() . '.language_id', '=', $language_id);
                }
            }
        );
    }

    public function scopeWithModel($query, $models = [], $only_online = false){
        if(!is_array($models)){
            $models = [$models];
        }

        $with = [];

        foreach ($models as $model) {
            $with[$model] = function($query) use ($only_online){
                $query->withDescription();
                if($only_online){
                    $query->online();
                }
            };
        }

        $query->with($with);
    }


    public function scopeOnline($query)
    {
        if(request()->get('preview') && auth()->user()){
            return $query;
        }

        return $query->where($this->getTable() . '.is_show', 1)->where($this->getTable() . '.publish_at', '<=', \Carbon\Carbon::now())->where(function($q){
            $q->whereNull($this->getTable() . '.offline_at')
                ->orWhere($this->getTable() . '.offline_at', '>=', \Carbon\Carbon::now());
        });
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where($this->getTable() . '.url_slug', $slug);
    }

    public function getStatus()
    {
        if($this->is_show){
            if(!$this->offline_at || $this->offline_at >= \Carbon\Carbon::now()){
                if($this->publish_at <= \Carbon\Carbon::now()){
                    return 'Online';
                }else{
                    return 'Scheduled';
                }
            }
        }
        return 'Offline';
    }

    public static function allOnlinePosts($order_by = ['arrange' => 'desc'])
    {
        $query = self::withDescription()->online();

        if($order_by){

            foreach ($order_by as $field => $order) {
                $query = $query->orderBy($field, $order);
            }
        }

        return $query;
    }


    public static function findPost($id, $language_id = 1)
    {
        $instance = new static;
        $posts = $instance->_getBasePostQueryBuilder($language_id)
            ->where('t1.id', $id);
        return $posts;
    }

    public static function findPostBy($field, $field_value, $language_id = 1)
    {
        $instance = new static;
        $posts = $instance->_getBasePostQueryBuilder($language_id)
            ->where($field, $field_value);
        return $posts;
    }

    public function getTableColumns($table = false)
    {   
        if(!$table){
            $table = $this->getTable();
        }
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    public function getLanguages(){
        return Language::where('active', 1)->arrange()->get();
    }

    public function descriptions()
    {
        $description_class = $this->getDescriptionModel();
        if(class_exists($description_class)){
            return $this->hasMany($description_class, 'description_id');
        }        
        return false;
    }

    public function language($language_id = null)
    {
        $description_class = $this->getDescriptionModel();
        if(class_exists($description_class)){
            if(!$language_id){
                $current_language_id = current_lang()->id;
                $language_id = $current_language_id ? $current_language_id : 1;
                return $this->hasOne($description_class, 'description_id')->where('language_id', $language_id);
            }
            return $this->hasOne($description_class, 'description_id')->where('language_id', $language_id)->first(); 
        }
    }

    public function created_user()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->select(['id', 'name', 'username']);
    }

    public function updated_user()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->select(['id', 'name', 'username']);
    }

    public function medias(){
        return $this->belongsToMany(Media::class, PostMedia::getInstance()->getTable(), 'post_id', 'media_id')->withPivot('type', 'post_table', 'post_model', 'details', 'language_id')->where('post_table', $this->getTable());
    }

    public function seo()
    {
        $id = request()->get('id');
        if($id){
            return $this->hasOne(Seo::class, 'post_id')->where(['post_table' => $this->getTable() ,'post_id' => $id]);
        }
        return $this->hasOne(Seo::class, 'post_id')->where('post_table', $this->getTable());
    }

    public function getMedias($language_id = null, $type = null, $return_mutli = true){
        $medias = $this->medias->groupBy(function ($media, $key) {
            return $media->pivot->language_id;
        })->transform(function($medias) {
            return $medias->groupBy(function ($media, $key) {
                return $media->pivot->type;
            })->sortByDesc('arrange');;
        });

        if($language_id){

            if($type){

                if(!isset($medias[$language_id][$type])){
                    return $return_mutli ? array() : null;
                }

                if(!$return_mutli){
                    return count($medias[$language_id][$type]) ? $medias[$language_id][$type][0] : null;
                }

                return $medias[$language_id][$type];
            }

            return $medias[$language_id];
        }


        return $medias;
    }

    public function getMedia($type = null, $return_mutli = false, $language_id = 1){
        return $this->getMedias($language_id, $type, $return_mutli);
    }

    public function saveRelation($data, $customData, $model){


        if(!isset($data['relation'])){
            return false;
        }
        $all_table = collect(array_keys($data['relation']))->except(['users', 'role'])->toArray();
        $all_languages = \App\Language::where('active', 1)->get();
        foreach ($all_table as $key => $table) {

            $modelData = $data['relation'][$table];

            // auto save seo when empty
            if($table == 'seo'){
                foreach ($all_languages as $language) {
                    $modelData = $this->saveSeo($modelData, $data, $language);
                }
            }

            $modelData['post_table'] = $this->getTable();

            $modelData['post_id'] = $this->id;
            $modelClass = $this->getModelByTable($table);
            $relationModel = new $modelClass;
            $relationModel = $relationModel->saveModel($modelData);
            if(isset($modelData['post_id_key'])){
                $model->{$modelData['post_id_key']} = $relationModel->id;
                $model->save();
            }

            if(isset($modelData['medias'])){
                PostMedia::saveWith($modelData['medias'], $relationModel);
            }

        }

    }

    public function saveSeo($modelData, $data, $language){
        if(empty($modelData['languages'][$language->id]['title'])){
            $modelData['languages'][$language->id]['title'] = $this->getDefaultSeoTitle($data, $language);
        }
        if(empty($modelData['languages'][$language->id]['description'])){
            $modelData['languages'][$language->id]['description'] = $this->getDefaultSeoDescription($data, $language);
        }

        return $modelData;
    }

    public function getDefaultSeoTitle($data, $language){
        return isset($data['languages'][$language->id]['title']) ? strip_tags($data['languages'][$language->id]['title']) : '';
    }

    public function getDefaultSeoDescription($data, $language){
        return isset($data['languages'][$language->id]['description']) ? strip_tags($data['languages'][$language->id]['description']) : '';
    }

    public function setUrlSlug($data){

        $id = $data['id'] ?? '';

        if(!isset($data['url_slug']) || empty($data['url_slug'])){
            $title = $data['languages'][1]['title'] ?? '';
            $data['url_slug'] = $this->genUrlSlug($title, $id);
        }else{
            $data['url_slug'] = $this->genUrlSlug($data['url_slug'], $id);
        }

        return $data;
    }

    public function genUrlSlug($title, $id = '', $extra_params = []){
        $id = $id ?: $this->id;

        $url_slug = url_slug($title);
        $related_slugs = $this->where('id', '!=', $id)->where('url_slug', 'like', ($url_slug . '%'))->extraUniqueSlugConditions($extra_params)->get()->pluck('url_slug')->filter(function($value, $key) use($url_slug){
            return  $value == $url_slug || preg_match("/^" . $url_slug . "\-[0-9]+/", $value);
        });

        if($related_slugs->isEmpty()){
            return $url_slug;
        }

        $i = 1;
        $new_url_slug = $url_slug;

        while($related_slugs->contains($new_url_slug)){
            $new_url_slug = $url_slug . '-' . ++ $i;
        }
        
        return $new_url_slug;

    }

    public static function createUrlSlug($title, $id = '', $extra_params = []){
        return (new static)->genUrlSlug($title, $id, $extra_params);
    }

    public function scopeExtraUniqueSlugConditions($query, $extra_params = []){
        return $query;
    }

    public static function saveModel($data){

        $model = self::firstOrNew(['id' => $data['id']]);

        $table_columns = $model->getTableColumns();

        if($model->has_slug && collect($table_columns)->search('url_slug')){
            $data = $model->setUrlSlug($data);
        }

        if(empty($model->id)){
            $order_by_key = $model->getOrderByKey();
            $order_by_key_type = $model->getOrderByKeyType();
            
            if($order_by_key_type == 'int'){
                $max_arrange = $model::max($order_by_key);
                $model->$order_by_key = $max_arrange + 1;
            }
        }
        
        foreach (collect($data)->only($table_columns) as $field_key => $value) {

            if($field_key == 'url_slug' && empty($value)){
                continue;
            }

            $model->$field_key = $value;

        }


        if(isset($data['repeater'])){
            $model = $model->saveRepeater($model, $data['repeater'], $table_columns);
        }


        $model->save();
        
        if($model->descriptions() && isset($data['languages'])){
            $languages = $model->getLanguages();
            $modelDescriptionClass = get_class($model->descriptions()->getRelated());
            $description_table_columns = $model->getTableColumns($modelDescriptionClass::getInstance()->getTable());
            foreach ($languages as $language) {
                $desc_data = collect($data['languages'][$language->id])->only($description_table_columns)->toArray();
                $modelDescriptionClass::saveModel($model->id, $language->id, $desc_data);
            }
        }

        return $model;
    }


    public function saveRepeater($model, $repeaters, $table_columns){

        foreach ($repeaters as $field_name => $repeater_data) {
            if(in_array($field_name, $table_columns)){
                if(!$repeater_data ){
                    $model->$field_name = null;
                }else{
                    $model->$field_name = array_values($repeater_data);
                }
            }
        }
        return $model;
    }

    public static function deleteBy($id){
        $model = parent::findOrFail($id);

        return $model->deleteModel();
    }

    public function deleteModel(){
        if($this->descriptions()){
            foreach ($this->descriptions as $description) {
                $description->delete();
            }
        }
        if($this->medias){
            foreach ($this->medias as $media) {
                $media->pivot->delete();
            }
        }

        if($this->seo){
            $this->seo->deleteModel();
        }

        $this->delete();
        return $this;
    }

    public static function saveArrangementBy($ids){
        $total = count($ids);
        foreach ($ids as $index => $id) {
            $model = self::find($id);
            $model->saveArrangement($total, $index);
        }
        return $model ?? false;
    }

    public function saveArrangement($total, $index){
        $this->arrange = $total - $index;
        $this->save();
        return $this;
    }

    public function initRepeater($return_all_languages = false, $medias_callback = false){

        $languages = \App\Language::where('active', 1)->get();

        $language_ids = $languages->pluck('id')->toArray();

        foreach ($this->casts as $field => $type) {
            if($type == 'array' && isset($this->attributes[$field]) && $this->attributes[$field]){
                $this->attributes[$field] = json_decode($this->attributes[$field], true);
                foreach ($this->attributes[$field] as $repeater_index => $repeater_row) {
                    if(isset($repeater_row['medias']) && $repeater_row['medias']){
                        foreach ($repeater_row['medias'] as $language_id => $type_medias) {
                            foreach ($type_medias as $type => $media_ids) {
                                $medias = \App\Media::whereIn('id', $media_ids)->orderByRaw('FIELD (id, ' . implode(", ", $media_ids) . ')')->get();
                                
                                if($medias_callback){
                                    $medias = $medias_callback($field, $type, $medias);
                                }

                                $this->attributes[$field][$repeater_index]['medias'][$language_id][$type] = $medias;
                            }
                        }

                        if(!$return_all_languages){
                            $medias = ($this->attributes[$field][$repeater_index]['medias'][current_lang()->id] ??$this->attributes[$field][$repeater_index]['medias'][1]) ?? [];
                            $this->attributes[$field][$repeater_index]['medias'] = $medias;
                        }
                    }

                    foreach ($language_ids as $language_id) {
                        if(isset($repeater_row['languages']) && !isset($repeater_row['languages'][$language_id])){
                            $this->attributes[$field][$repeater_index]['languages'][$language_id] = [];
                        }
                    }

                    if(!$return_all_languages){

                        $current_lang_data = $this->attributes[$field][$repeater_index]['languages'][current_lang()->id] ?? [];
                        $current_lang_new_data = [];

                        foreach ($current_lang_data as $lang_field => $value) {
                            $this->attributes[$field][$repeater_index][$lang_field] = $value;
                        }

                        unset($this->attributes[$field][$repeater_index]['languages']);
                    }
                }
                $this->attributes[$field] = json_encode($this->attributes[$field]);
            }
        }
    }

    public function getListingUrl($params = []){
        return false;
    }

    public function getDetailUrl($params = []){
        return false;
    }

    public function canOnline(){
        return true;
    }

    public function addDefaultMeta(){
        $columns = $this->getTableColumns();
        foreach ($columns as $column) {
            $this->$column = null;
        }

        if($this->hasDescriptionModel()){
            $columns = $this->getTableColumns($this->descriptionTable());
            $language_ids = active_langs()->pluck('id')->toArray();
            $languages_data = [];
            foreach ($language_ids as $language_id) {
                $languages_data[$language_id] = [];

                foreach ($columns as $column) {
                    $languages_data[$language_id][$column] = null;
                }
            }
            $this->languages = $languages_data;
        }

        return $this;
    }

    public function autoSaveAttritubes(){
       
    }

    public static function tableTreeOptionHtml($check_id = 0, $parent_id = 0, $order_by = 'arrange|desc', $html = '', $level = 0)
    {

        list($order_by_field, $order_by_order) = explode("|", $order_by);

        if(self::getInstance()->isChild()){
            $records = self::getInstance()->getCategoryModel()::where('parent_id', $parent_id)->orderBy($order_by_field, $order_by_order)->get();
        }else{
            $records = self::where('parent_id', $parent_id)->orderBy($order_by_field, $order_by_order)->get();
        }
        
        foreach ($records as $record){
            $html .= '<option data-level="' . $level . '" value="' . $record->id . '"' . (($check_id == $record->id) ? 'selected="selected"' : '') . '>'  . $record->title . '</option>';

            if ($record->has_child_category){
                $html .= self::tableTreeOptionHtml($check_id, $record->id, $order_by, '', ++$level);
            }

        }
        return $html;
    }

    private function _getBasePostQueryBuilder($language_id = 1){
        $queryBuilder = DB::table($this->getTable() . ' as t1')
            ->leftjoin($this->getDescriptionTable() . ' as t2', function ($join) use ($language_id) {
            $join->on('t1.id', '=', 't2.description_id')
                ->where('t2.language_id', '=', $language_id);
        });
        return $queryBuilder;
    }
}

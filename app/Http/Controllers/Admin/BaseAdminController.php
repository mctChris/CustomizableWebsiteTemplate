<?php

namespace App\Http\Controllers\Admin;

use Request as StaticRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Route;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\MediaFolder;
use App\Media;
use App\PostMedia;
use Illuminate\Support\Facades\Validator;

class BaseAdminController extends Controller
{
    protected $section;
    protected $modelClass;
    protected $model;
    protected $modelNamespace = 'App';
    protected $defaultPaginate = 20;
    protected $exceptOnSave = [];
    protected $listingVisibleFieldsOnJson = ['id', 'title'];

    public function __construct()
    {
        $this->modelClass = $this->getModel();
        if($this->modelClass){
            $this->model = $this->modelClass::getInstance();
            $this->model->config_model = true;
            $this->section = $this->model->getSection();
        }else{
            $this->section = StaticRequest::segment(2);
        }

    }

    public function getConfig($key = ''){

        if($this->modelClass && $this->model->isChild()){
            $categoryControllerClass = __NAMESPACE__ . '\\' .$this->model->getCategoryModel()::getInstance()->getControllerName();
            $controller = new $categoryControllerClass;
        }else{
            $controller = $this;
        }

        $config = [
            'csrf_token' => csrf_token(),
            'base_url' => url('/'),
            'route_action' => currentRouteAction(),
            'id' => StaticRequest::route('id') ? StaticRequest::route('id') : "",
            'page_name' => $this->getPageName(),
            'section' => $this->section(),
            'links' => $this->getLinks(),
            'breadcrumb' => $this->getBreadcrumb(),
            'breadcrumb_section' => $controller->getBreadcrumbSection(),
            'breadcrumb_page_name' => $controller->getPageName(),
            'show_main_breadcrumb' => $this->getShowMainBreadcrumb(),
            'media_library' => MediaController::config(),
            'endpoints' => $this->getEndpoints(),
            'model' => $this->model ?? $this->modelClass,
            'route_sections' => $this->getRouteList(),
            'menus' => AdminMenuController::getMenus(),
            'route_section' => collect(explode('.', \Request::route()->getName()))->last(),
        ];


        if(!empty($key)){
            return $config[$key] ?? null;
        }

        return $config;

    }

    public function getLinks($url = false){
        if(!$this->modelClass){
            return false;
        }
        $query_string = $this->getQueryString($url);

        if($url){
            preg_match("/\/parent_id\/([0-9]+)/", $url, $matches);
            $parent_id = isset($matches[1]) ? $matches[1] : 0;
        }else{
            $parent_id = StaticRequest::route('parent_id') ? StaticRequest::route('parent_id') : 0;
        }

        if( ($url && preg_match("/\/(listing|detail)/", $url, $matches)) || (!$url && Route::has($this->section('listing')))){
            if($parent_id){
                $back_url =  route($this->section('listing'), $parent_id);
            }else{
                $back_url =  route($this->section('listing'));
            }
            $back_url .= $query_string;
        }else{
            $back_url = false;
        }
        $listingLinks = $this->_getListingLinks($parent_id);
        return [
            'listing' => [
                'add' => $listingLinks['add'] . $query_string,
                'back' => $listingLinks['back'] . $query_string,
                'arrangement' => $listingLinks['arrangement'] . $query_string,
            ],
            'detail' => [
                'back' => $back_url
            ],
            'arrangement' => [
                'back' => $back_url
            ]
        ];
    }
    
    public function getQueryString($url = false){
        if($url){
            $url_arr = explode('?', $url);
            return isset($url_arr[1]) ? ('?' . $url_arr[1]) : '';
        }

        $referer = request()->server('HTTP_REFERER');
        if(request()->isMethod('post')){
            $referer_arr = explode('?', $referer);
            $query_string = isset($referer_arr[1]) ? ('?' . $referer_arr[1]) : '';
        }else{
            $query_string = !empty($_GET) ? ('?' . http_build_query($_GET)) : '';
        }
        
        return $query_string;
    }


    public function getEndpoints(){
        return [
            'save' => Route::has($this->section('save')) ? route($this->section('save')) : false,
            'bulk_action' => Route::has($this->section('bulk_action')) ? route($this->section('bulk_action')) : false,
        ];
    }

    private function _getListingLinks($parent_id = false){

        if($parent_id === false){
            $parent_id = StaticRequest::route('parent_id') ? StaticRequest::route('parent_id') : 0;
        }
        
        $add_url = $parent_id ? route($this->section('detail'), ['id' => 0, 'parent_id' => $parent_id]) : route($this->section('detail'), ['id' => 0]);

        if($this->model->isCategory()){ // category
            $record = $this->modelClass::find($parent_id);
            if(!$record || $record->parent_id == $parent_id){
                $back_url = false;
            }else{
                $back_url = route($this->section('listing'), $record->parent_id);
            }
        }else if($this->model->isChild()){ // child of category
            if($parent_id){
                $category_model_class = $this->model->getCategoryModel();
                $record = $category_model_class::findOrFail($parent_id);
                $back_url = route('admin.' . $record->getTable() . '.listing', $record ? $record->parent_id : 0);
            }else{
                $back_url = false;
            }
        }else{ // no level
            $back_url = route($this->section('listing'));
        }
        
        $arrangement_url = route($this->section('arrangement'), $parent_id);
        return [
            'add' => $add_url,
            'back' => $back_url,
            'arrangement' => $arrangement_url
        ];
    }

    public function section($page = ''){
        if(!empty($page)){
            return 'admin.' . $this->section . '.' . $page;
        }
        return $this->section;
    }

    public function getPageName(){
        $pageName = preg_replace('/(?<!\ )[A-Z]/', ' $0', $this->getControllerName());
        return $pageName;
    }

    public function getBreadcrumbSection(){
        return $this->section();
    }

    public function getShowMainBreadcrumb(){
        return true;
    }

    public function getModel(){
        $modelName = $this->modelNamespace . "\\" . $this->getControllerName();
        return class_exists($modelName) ? $modelName : false;
    }

    public function getTable(){
        return $this->getModel() ? $this->getModel()::table() : false;
    }

    public function getControllerName(){
        return str_replace('Controller', '', (new \ReflectionClass($this))->getShortName());
    }

    public function getDescriptionModel(){
        return $this->getModel() ? $this->modelClass::descriptionModel() : false;
    }

    public function getTableColumns($table)
    {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }

    public function getBreadcrumb(){
        if(!$this->modelClass){
            return [];
        }
        $breadcrumb = [];
        $parent_id = StaticRequest::route('parent_id') ? StaticRequest::route('parent_id') : 0;
        if($this->model->isCategory() || $this->model->isChild()){

            if($this->model->isCategory()){
                $record = $this->modelClass::find($parent_id);
            }else{
                $categoryClass = $this->model->getCategoryModel();
                $record = $categoryClass::find($parent_id);
            }
            
            if($record){
                $this->setLinks($record);
                array_push($breadcrumb, [
                    'title' => $record->title,
                    'link' => $record->link,
                ]);

                while($record->parent){
                    $this->setLinks($record->parent);
                    array_push($breadcrumb, [
                        'title' => $record->parent->title,
                        'link' => $record->parent->link,
                    ]);
                    $record = $record->parent;
                }
                $breadcrumb = array_reverse($breadcrumb);
            }
            
        }
        return $breadcrumb;
    }

    public function getLanguages(){
        return $this->model->getLanguages();
    }

    public function getPosts($order_by = ['arrange' => 'desc'], $paginate = false, $language_id = true, $auto_get = true){

        $modelClass = $this->getModel();

        $this->authorize('view', $this->model);

        $model = new $modelClass;

        $query = $model;
                
        $table_columns = collect($model->getTableColumns());

        if($table_columns->search('created_by')  !== false){
            $query = $query->with('created_user');
        }

        if($table_columns->search('updated_by')  !== false){
            $query = $query->with('updated_user');
        }
        

        if($sort_field_direction = $this->getValidSortableField()){
            $query = $query->orderBy($sort_field_direction['field'], $sort_field_direction['direction']);
        }else{
            if($order_by){
                foreach ($order_by as $field => $order) {
                    $query = $query->orderBy($field, $order);
                }
            }
        }

        if($model->hasDescriptionModel()){
            $query = $query->withDescription($language_id);
        }

        $query = $this->postsQuery(\Route::getCurrentRoute()->parameters(), $query);

        if($auto_get){
            if($paginate === -1){
                $records = $query->get();
            }else{
                $records = $query->paginate($paginate ? $paginate : $this->defaultPaginate);
            }

            $records = $this->getPostsAfter($records);
        }

        return $records ?? $query;
    }

    public function getAllPosts($order_by = ['arrange' => 'desc'], $language_id = true, $auto_get = true){
        return $this->getPosts($order_by, -1, $language_id, $auto_get);
    }
    
    public function postsQuery($route_params, $query){
        return $query;
    }

    public function getPostsAfter($records){
        foreach ($records as $key => $record) {
            $this->setLinks($record);
            $record->setVisible($this->getListingVisibleFieldsOnJson());
        }

        return $records;
    }

    public function getListingVisibleFieldsOnJson(){
        return $this->listingVisibleFieldsOnJson;
    }

    public function getPost($id = ''){
        $modelClass = $this->getModel();
        $model = new $modelClass;

        if(empty($id)){
            $model->addDefaultMeta();
            $model->is_show = 1;
          
        }else{
            $table_columns = collect($model->getTableColumns());

            if($table_columns->search('created_by') !== false){
                $model = $model->with('created_user');
            }

            if($table_columns->search('updated_by')  !== false){
                $model = $model->with('updated_user');
            }

            $model = $model->findOrFail($id);
            $model->languages = $model->descriptions() ? $model->descriptions->keyBy('language_id') : false;

        }
        $this->authorize('view', $model);
        return $model;
    }

    public function getSortableFields(){
        return [];
    }

    public function getValidSortableField(){
        if($sort = request()->get('sort')){
            preg_match("/(.*)_(asc|desc)/", $sort, $matches);

            if(count($matches) != 3){
                return false;
            }

            $sort_field = $matches[1];
            $sort_direction = $matches[2];

            foreach ($this->getSortableFields() as $name => $field) {
                if($sort_field == $name){
                    return ['field' => $field, 'direction' => $sort_direction];
                }
            }
            
        }

        return false;
    }

    protected function setTitle($record){
        if($record->language() && $record->language){
            $record->title = $record->language->title;
        }
        if(empty($record->title)){
            $record->title = '(no title)';
        }
    }

    protected function setLinks($record){
        $parent_id = StaticRequest::route('parent_id') ? StaticRequest::route('parent_id') : 0;
        $query_string = $this->getQueryString();

        if(isset($record->parent_id)){
            if(isset($record->has_child_category)){
                $record->detail_link = route($this->section('detail'), ['id' => $record->id, 'parent_id' => $parent_id]) . $query_string;
                if($record->has_child_category){
                    if(!preg_match("/_category/", $this->section('listing'))){
                        $section = 'admin.' . $this->section() . '_category.listing';
                    }else{
                        $section = $this->section('listing');
                    }
                    $record->link = route($section, $record->id) . $query_string;
                }else{
                    $record->link = route('admin.' . str_replace('_' . $this->model->getCategorySuffix() , '', $this->section()) . '.listing', $record->id) . $query_string;
                }
            }else{
                $record->detail_link = $record->link = route($this->section('detail'), ['id' => $record->id, 'parent_id' => $parent_id]) . $query_string; 
            }
        }else{
            $record->detail_link = $record->link = route($this->section('detail'), $record->id) . $query_string;
        }
    }


    public function tableTree($parent_id = 0, $order_by = 'arrange|desc'){
        $modelClass = $this->getModel();
        return $modelClass::tableTree($parent_id, $order_by);
    }

    public function tableTreeOptionHtml($check_id = 0){
        $modelClass = $this->getModel();
        return $modelClass::tableTreeOptionHtml($check_id);
    }

    public function getRouteList()
    {
        $result = collect();

        $routeCollection = collect(\Route::getRoutes())->map(function ($route) {
            preg_match("/" . config('appcustom.admin_path') .  "\/(.*)/", $route->getAction('prefix'), $matches);
            return $matches[1] ?? null; 
        })->reject(function($value, $key){
            return $value == null;
        })->unique()->toArray();

        foreach ($routeCollection as $key => $value) {
            $sectionName = ucwords(str_replace('_', ' ', $value));
            $result->put($value, $sectionName);
        }

        $result = $result->sort();

        return $result;
    }

    public function listing(){
        if($this->model->is_page){
            $modelClass = $this->getModel();
            $model = $modelClass::first();
            $redirect_url = $model ? route($this->section('detail'), $model->id) : route($this->section('detail'));
            redirect_now($redirect_url);
        }
    }

    public function detail($id = ''){
        if($this->model->is_page){
            if(empty($id)){
                $modelClass = $this->getModel();
                $model = $modelClass::first();
                if($model){
                    redirect_now(route($this->section('detail'), $model->id));
                }
            }
        }
    }

    public function save(Request $request, $customData = [])
    {
        $modelClass = $this->getModel();

        // check if the current user has 'update' permission on this section
        
        $this->authorize('update', $this->model);

        $result = $this->beforeSave($request, $customData);

        if(is_array($result)){
            $customData = $result;
        }else{
            return $result;
        }

        // remove the fields that added to exception array
        $input_data = $request->except($this->exceptOnSave);

        // merge custom data of child controller
        $input_data = collect($input_data)->merge($customData);

        DB::beginTransaction();

        // save the model
        $model = $this->saveModel($input_data);

        // if any medias selected, save media to this post
        if($medias = $request->input('medias')){
            PostMedia::saveWith($medias, $model);
        }

        // save relation for this post
        $model->saveRelation($input_data, $customData, $model);

        $this->afterSave($request, $customData, $model);

        DB::commit();

        return $this->saveRedirectTo($request, $customData, $model);
    }

    public function beforeSave(Request $request, $customData = []){
        return $customData;
    }

    public function afterSave(Request $request, $customData = [], $model){

    }

    public function saveRedirectTo(Request $request, $customData, $model){
        $query_string = $this->getQueryString();

        if(isset($model->parent_id)){
            $redirect_url = route($this->section('detail'), ['id' => $model->id, 'parent_id' => $model->parent_id]);
        }else{
            $redirect_url = route($this->section('detail'), $model->id);
        }

        $redirect_url .= $query_string;

        $links = $this->getLinks($redirect_url);

        return redirect($redirect_url)->with(['status' => 'success', 'message' => $this->getSaveSuccessMessage($model), 'actions_html' => $this->getSaveSuccessActionsHtml($model, $links)]);
    }

    public function getSaveSuccessMessage($model){
        return 'Save Successfully!';
    }

    public function getSaveSuccessActions($model, $links){

        if($model->is_page){
            return [];
        }

        return [
            'Back to Listing Page' => $links['detail']['back'],
            'Create New' => $links['listing']['add'],
        ];
        
    }

    public function getSaveSuccessActionsHtml($model, $links){
        $actions = $this->getSaveSuccessActions($model, $links);
        $html = '';
        $i = 1;
        foreach ($actions as $action_name => $action_url) {

            $html .= "<a class='underline' href='$action_url'>$action_name</a>";

            if($i == count($actions)){
                $html .= '.';
            }else{
                if($i == count($actions) - 1){
                    if(count($actions) == 2){
                        $html .= ' or ';
                    }else{
                        $html .= ', or ';
                    }
                }else{
                    $html .= ', ';
                }
            }

            $i++;
        }

        return $html;
    }

    public function registerExceptOnSave(...$fields) {
        if(is_array($fields[0])){
            $fields = $fields[0];
        }
        $this->exceptOnSave = array_unique(array_merge($this->exceptOnSave, $fields));
    }

    protected function saveModel($input_data){
        $modelClass = $this->getModel();
        return $modelClass::saveModel($input_data);
    }

    public function bulkAction(Request $request){
        Validator::make($request->all(), [
            'id' => 'required',
            'action' => 'required'
        ])->validate();

        $action = $request->get('action');

        switch ($action) {
            case 'delete':
                return $this->delete($request);
                break;
            case 'online':
                return $this->bulkUpdateStatus($request->get('id'));
                break;
            case 'offline':
                return $this->bulkUpdateStatus($request->get('id'), false);
                break;
            case 'duplicate':
                return $this->bulkDuplicate($request->get('id'));
                break;
            default:
                break;
        }
    }

    public function bulkDuplicate($ids)
    {

        foreach ($ids as $id) {
            $model = $this->duplicate($id, false);
        }

        if(!$model){
            return redirect($redirect_url . $query_string)->with(['status' => 'failure', 'message' => 'Duplicate Failure!']);
        }

        $query_string = $this->getQueryString();

        if(isset($model->parent_id)){
            $redirect_url = route($this->section('listing'), $model->parent_id);
        }else{
            $redirect_url = route($this->section('listing'));
        }

        return redirect($redirect_url . $query_string)->with(['status' => 'success', 'message' => 'Duplicate Successfully!']);

    }

    public function duplicate($id, $with_redirect = true){
        $modelClass = $this->getModel();
        $model = $modelClass::findOrFail($id);

        $this->authorize('duplicate', $model);

        \DB::beginTransaction();
        $new_model = $this->duplicateProcess($model);
        \DB::commit();

        if($with_redirect){
            return $this->duplicateRedirectTo($new_model);
        }

        return $new_model;
    }

    public function duplicateProcess($model){
        return $model;
    }

    public function duplicateRedirectTo($model){
        $query_string = $this->getQueryString();

        if(isset($model->parent_id)){
            $redirect_url = route($this->section('detail'), ['id' => $model->id, 'parent_id' => $model->parent_id]);
        }else{
            $redirect_url = route($this->section('detail'), $model->id);
        }

        return redirect($redirect_url . $query_string)->with(['status' => 'success', 'message' => 'Duplicate Successfully!']);
    }

    public function bulkUpdateStatus($ids, $isOnline = true){
        $modelClass = $this->getModel();
        $this->authorize('update', $this->model);

        DB::beginTransaction();

        $success_count = 0;
        $failure_count = 0;

        $result = [];

        foreach ((array)$ids as $id) {
            $model = $modelClass::find($id);
            $this->authorize('update', $model);
            if($isOnline && $model->canOnline()){
                $model->is_show = 1;
                $model->publish_at = date('Y-m-d H:i:s');
                $model->offline_at = null;
                $model->save();
                $success_count ++;
            }elseif(!$isOnline){
                $model->is_show = 0;
                $model->save();
                $success_count ++;
            }else{
                $failure_count ++;
            }

            $result[] = [
                'id' => $model->id,
                'is_show' => $model->is_show
            ];
        }

        DB::commit();

        $query_string = $this->getQueryString();

        if(isset($model->parent_id)){
            $redirect_url = route($this->section('listing'), $model->parent_id);
        }else{
            $redirect_url = route($this->section('listing'));
        }

        if($success_count == 0){
            $response = ['status' => 'failure', 'message' => 'Update Failure!'];
        }else{
            if($failure_count != 0){
                $response = ['status' => 'success', 'message' => sprintf("Status Updated! Success: %d, Failure: %d", $success_count, $failure_count), 'result' => $result];
            }else{
                $response = ['status' => 'success', 'message' => 'Status Updated!', 'result' => $result];
            }
        }

        if(request()->ajax()){
            return $response;
        }

        return redirect($redirect_url . $query_string)->with($response);

    }

    public function delete(Request $request){
        $modelClass = $this->getModel();

        $this->authorize('delete', $this->model);

        Validator::make($request->all(), [
            'id' => 'required'
        ])->validate();

        $input_data = $request->only('id');

        if(!is_array($input_data['id'])){
            $input_data['id'] = [$input_data['id']];
        }

        DB::beginTransaction();

        foreach ($input_data['id'] as $id) {
            $model = $modelClass::find($id);
            $this->authorize('delete', $model);
            $model->deleteModel();
        }

        $this->afterDelete($request, $model);

        DB::commit();

        $query_string = $this->getQueryString();

        if(isset($model->parent_id)){
            $redirect_url = route($this->section('listing'), $model->parent_id);
        }else{
            $redirect_url = route($this->section('listing'));
        }

        return redirect($redirect_url . $query_string)->with(['status' => 'success', 'message' => 'Delete Successfully!']);
    }

    public function afterDelete(Request $request, $model){

    }

    public function save_arrangement(Request $request){
        $input_data = $request->only('id');
        $modelClass = $this->getModel();
        $this->authorize('update', $this->model);
        if(isset($input_data['id'])){
            $model = $modelClass::saveArrangementBy($input_data['id']);
        }

        $query_string = $this->getQueryString();

        if(isset($model->parent_id)){
            $redirect_url = route($this->section('listing'), $model->parent_id);
        }else{
            $redirect_url = route($this->section('listing'));
        }

        return redirect($redirect_url . $query_string)->with(['status' => 'success', 'message' => 'Save Arrangement Successfully!']);
    }

}

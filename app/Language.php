<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Language extends BaseModel
{
    protected $table = 'language';

    public static function getCurrentLanguage()
    {
        return self::where('code', \App::getLocale())->first();
    }

    public static function getAllLanguageRoutes()
    {
        $all_languages = \App\Language::where('active', 1)->get();

        $current_uri = \Request::getRequestUri();
        foreach ($all_languages as $key => $language) {
            if($current_uri == '/' || $current_uri == ('/' . \App::getLocale())){
                if($language->code == config('app.default_locale')){
                    $language->url = url('/');
                }else{
                    $language->url = url('/' . $language->code);
                }
            }else{
                $language->url = url(preg_replace("/^\\/" . \App::getLocale() . "/", '/' . $language->code, $current_uri));
            }
        }

        return $all_languages;
    }

    public static function indexRoute()
    {
        if(\App::getLocale() !== config('app.default_locale')){
            \Route::get('/', 'IndexController@index')->name('index');
        }else{
            if(active_langs()->count() > 1){
                \Route::get('/', function(){
                    $query = http_build_query(request()->query());
                    if(!empty($query)){
                         $query = '?' . $query;
                    }
                    return redirect('/' . $query, 301);
                });
            }
        }
    }

}

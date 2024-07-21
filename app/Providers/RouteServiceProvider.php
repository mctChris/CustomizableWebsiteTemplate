<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $language_except_route;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */


    public function boot()
    {
        //

        parent::boot();

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(Router $router, Request $request)
    {

        $this->language_except_route = [
            null,
            'assets',
            'storage',
            'public',
            'telescope',
            config('appcustom.admin_path'),
        ];

        $url_first_segment = $request->segment(1);

        if(empty($url_first_segment)){
            $locale = config('app.default_locale');
        }else{
            if(config('app.language_use_database')){

                $is_exist_lang =  active_langs()->where('code', $url_first_segment)->first();

                if($is_exist_lang){
                    $locale = $url_first_segment;
                }else{
                    $locale = config('app.default_locale');

                    if(!in_array($url_first_segment, $this->language_except_route) && \App\Language::where('active', 1)->count() > 1){
                        $path = str_replace($request->root(), '', $request->fullUrl());
                        $redirect_url = url($locale . $path);
                        redirect_now($redirect_url);
                    }
                }
            }else{
                // To do
            }
        }

        App::setLocale($locale);
        $this->mapApiRoutes();
        $this->mapWebRoutes($locale, $url_first_segment);
        $this->mapAdminRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes($locale, $url_first_segment)
    {

        Route::middleware('web')->get('/', $this->namespace . '\IndexController@index')->name('index');

        $routes = Route::middleware('web')
             ->namespace($this->namespace);
        if(\App\Language::where('active', 1)->count() > 1){
            $routes->prefix($locale);
        }
        $routes->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}

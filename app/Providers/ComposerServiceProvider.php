<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('errors::404', function($view){
            $controller = new \App\Http\Controllers\BaseFrontendController;
            $controller->output(function($data) use ($controller, $view){
                $view->with('seo', $controller->getSeo(lang('Page Not Found')));
                foreach ($data as $key => $value) {
                    $view->with($key, $value);
                }
            });
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

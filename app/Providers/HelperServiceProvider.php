<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        app()->singleton('current_lang', function ($app) {
            return \App\Language::getCurrentLanguage();
        });

        app()->singleton('active_langs', function ($app) {
            return \App\Language::where('active', 1)->arrange()->get();
        });

        app()->singleton('lang', function ($app) {
            return \App\Translation::withDescription()->get();
        });

        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once ($filename) ;
        }
    }
}

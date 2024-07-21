<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::include('admin.components.row', 'row');
        Blade::include('admin.components.repeater', 'repeater');

        // set default language
        $default_lang = \App\Language::where('default_language', 1)->pluck('code')->first();

        config(['app.locale' => $default_lang, 'app.default_locale' => $default_lang]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}

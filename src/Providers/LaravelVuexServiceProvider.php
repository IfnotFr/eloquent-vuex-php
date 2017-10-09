<?php

namespace Ifnot\LaravelVuex\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelVuexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/../helpers.php';
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

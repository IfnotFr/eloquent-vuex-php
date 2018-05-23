<?php

namespace Ifnot\EloquentVuex\Providers;

use Illuminate\Support\ServiceProvider;

class EloquentVuexServiceProvider extends ServiceProvider
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

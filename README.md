# Eloquent Vuex - WIP

**Realtime model synchronization between Vuex (VueJs) and Eloquent (Laravel)**

This package allows you to send eloquent events (create, update and delete) through laravel echo as vuex mutations in order to keep all your clients data in sync with your laravel database.

This package is designed for an easy integration without deep changes of your laravel backend and vuex frontend.

## Prerequisites

> Before using this package you should have a working Echo installation (client + server). [Please follow the official installation steps from the documentation](https://laravel.com/docs/5.5/broadcasting). You have to be able to send a ping from laravel and read it with Echo.

## Installation

    composer require ifnot/eloquent-vuex

As it is a WIP, you may want lower your stability options in your `composer.json` :

    "minimum-stability": "dev",
    "prefer-stable": true

Then add the service provider into your `config/app.php` *before your Application Service Providers (important)* :

    Ifnot\EloquentVuex\Providers\EloquentVuexServiceProvider::class,

## Quick Start

Listen your eloquent models for modifications into your `AppServiceProvider` :

```php
use Ifnot\EloquentVuex\Vuex;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Vuex::sync([
            App\User::class
            App\Car::class
        ]);
    }
}
```

## Fine tuning

These section may have many api changes during the wip and will be written when the api is nearly frozen.

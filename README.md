# Laravel Vuex - WIP

This package allows you to synchronize your Vuex stores with your Laravel database. Based on Laravel Echo, all the
Eloquent events are broadcasted to your Vuejs application and traduced in mutations for bring real time reactivity to your stores.

## Installation

    composer require ifnot/laravel-vuex

As it is a WIP, you may want lower your stability options in your `composer.json` :

    "minimum-stability": "dev",
    "prefer-stable": true

## Quick Start

> Important : before using this package you should have a working Echo installation (client + server). [Please follow the official installation steps from the documentation](https://laravel.com/docs/5.5/broadcasting). You have to be able to send a ping from laravel and read it with Echo.

Observe your reactive models in a service provider (for example `App\Providers\AppServiceProvider`) :

```php
use Ifnot\LaravelVuex\MutationBroadcaster;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ...
        
        MutationBroadcaster::observe([Car::class]);
    }
    
    // ...
}
```

All your events are now broadcasted through Echo. [You can now follow the installation instructions for your vuejs app.](https://github.com/Ifnot/laravel-vuex-js).

### How it works ?

We just assume that one table (with eloquent) is one vuex store. With this system we send the Eloquent
`creation`, `edition`, and `deletion` events into Echo broadcasts to handle these database changes your front-end app.

## Documentation

Take a look at the [Github Wiki](https://github.com/Ifnot/laravel-vuex-php/wiki) for more options.

## Example

There is two examples project demonstrating the package :

* [Server side (Laravel)](https://github.com/Ifnot/laravel-vuex-php-example)
* [Client side (Vuejs)](https://github.com/Ifnot/laravel-vuex-js-example)

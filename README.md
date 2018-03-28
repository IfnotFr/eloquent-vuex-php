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

Then add the service provider into your `config/app.php` **before your Application Service Providers (important)** :

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
            App\Car::class
        ]);
    }
}
```

## Fine tuning

Reference a `$store` property into your model for changing default event broadcasting behaviour :

```php
class Car extends Model
{
    public $store = CarStore::class;
    
    // ...
}

class CarStore extends Ifnot\EloquentVuex\Vuex\Store
{
    public function getCascadeRelations(): array
    {
        // Return relations to be updated when this model changes
        // Example : if we have $car->user, the following line will update the related model for every changes
        return ['users'];
    }
    
    public function getNamespace(): string
    {
        // Changes the default module name on the client-side
        return 'MyCars';
    }
    
    public function toArray(Model $model): array
    {
        // How the model is converted to event payload
        // Example : a savage way to call a fractal transformer
        $fractal = new \League\Fractal\Manager();
        $resource = new \League\Fractal\Resource\Item($model, new CarTransformer());
        return $fractal->createData($resource)->toArray()['data'];
    }
    
    public function getStates(Model $model): array
    {
        // Which state to be mutated on the client side
        // Example : use a private channel instead the public one
        // To be documented : there is many other states for your app logic
        return [
            new \Ifnot\EloquentVuex\Vuex\States\State($this, 'all', new Illuminate\Broadcasting\PrivateChannel('my-private'))
        ];
    }
}
```

> Keep in mind that you can see the default behaviour by [reading the default Store implementation](https://github.com/Ifnot/eloquent-vuex-php/blob/master/src/Vuex/Store.php)

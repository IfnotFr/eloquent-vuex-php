<?php
use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

if(!function_exists('model_event')) {
    function model_event(Model $model, $name, array $meta = [])
    {
        \Ifnot\LaravelVuex\ModelBroadcaster::fire($model, $name, $meta);
    }
}
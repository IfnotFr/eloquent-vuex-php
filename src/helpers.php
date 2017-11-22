<?php
use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

if(!function_exists('model_mutation')) {
    function model_mutation(Model $model, $name, array $meta = [])
    {
        \Ifnot\LaravelVuex\ModelBroadcaster::fire($model, $name, $meta);
    }
}
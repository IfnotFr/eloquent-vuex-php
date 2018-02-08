<?php
use Ifnot\EloquentVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

if(!function_exists('model_mutation')) {
    function model_mutation(Model $model, $name, array $meta = [])
    {
        \Ifnot\EloquentVuex\ModelBroadcaster::fire($model, $name, $meta);
    }
}
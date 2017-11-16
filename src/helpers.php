<?php
use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

if(!function_exists('model_mutation')) {
    function model_mutation(Model $model, $name, array $meta = [])
    {
        $store = $model->store ? new $model->store($model) : new Store($model);
        $store->$name($model, $meta);
    }
}
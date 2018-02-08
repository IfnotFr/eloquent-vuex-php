<?php

namespace Ifnot\EloquentVuex;

use Ifnot\EloquentVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

class ModelBroadcaster
{
    public static $ignored = [];

    public static function fire($model, $event, array $meta = [])
    {
        if(!self::isIgnored(get_class($model))) {
            $store = $model->store ? new $model->store($model) : new Store($model);
            $store->$event($model, $meta);
        }
    }

    public static function ignore(string $class)
    {
        self::$ignored[] = $class;
    }

    public static function isIgnored(string $class)
    {
        foreach(self::$ignored as $ignored) {
            if(fnmatch($ignored, $class)) {
                return true;
            }
        }

        return false;
    }
}
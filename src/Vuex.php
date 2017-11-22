<?php

namespace Ifnot\LaravelVuex;

class Vuex
{
    /**
     * Observe multiples models in order to broadcasts their events
     */
    public static function sync(array $models)
    {
        ModelObserver::observe($models);
    }

    /**
     * Ignore classes so all the events are muted.
     */
    public static function ignore()
    {
        foreach(func_get_args() as $arg) {
            if(is_callable($arg)) {
                $for = $arg;
            } elseif(is_array($arg)) {
                $classes = $arg;
            } elseif(is_string($arg)) {
                $classes = [$arg];
            }
        }

        if(isset($for)) {
            $ignored = ModelBroadcaster::$ignored;
        }

        if(isset($classes)) {
            foreach($classes as $class) {
                ModelBroadcaster::ignore($class);
            }
        } else {
            ModelBroadcaster::ignore('*');
        }

        if(isset($for)) {
            call_user_func($for);
            ModelBroadcaster::$ignored = $ignored;
        }
    }
}
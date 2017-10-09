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
     * Ignore a class so all the events are muted.
     */
    public static function ignore($name)
    {
        ModelObserver::$ignoreClasses[] = $name;
    }
}
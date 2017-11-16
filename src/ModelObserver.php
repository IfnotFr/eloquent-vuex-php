<?php

namespace Ifnot\LaravelVuex;

use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    public static $ignoreClasses = [];

    /**
     * Observe multiples models in order to broadcasts their events
     */
    public static function observe(array $models)
    {
        foreach ($models as $model) {
            $model::observe(static::class);
        }
    }

    /**
     * Listen to the Model created event.
     */
    public function created(Model $model)
    {
        $this->fire($model, 'create');
    }

    /**
     * Listen to the Model updated event.
     */
    public function updated(Model $model)
    {
        $this->fire($model, 'update');
    }

    /**
     * Listen to the Model deleted event.
     */
    public function deleted(Model $model)
    {
        $this->fire($model, 'delete');
    }

    /*
     * Fire the event to the model Store
     */
    protected function fire($model, $event)
    {
        if (! $this->isModelIgnored($model)) {
            model_mutation($model, $event);
        }
    }

    protected function isModelIgnored(Model $model)
    {
        return in_array(get_class($model), self::$ignoreClasses);
    }
}

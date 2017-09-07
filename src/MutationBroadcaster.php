<?php

namespace Ifnot\LaravelVuex;

use Illuminate\Database\Eloquent\Model;

class MutationBroadcaster
{
    protected static $delayedBroadcasters = [];
    protected static $ignoreClasses = [];

    /**
     * Observe multiples models in order to broadcasts their events
     */
    public static function observe(array $models)
    {
        foreach($models as $model) {
            $model::observe(static::class);
        }
    }

    /**
     * Ignore a class so all the events are muted.
     */
    public static function ignoreClass($name)
    {
        self::$ignoreClasses[] = $name;
    }

    /**
     * Listen to the Model created event.
     */
    public function created(Model $model)
    {
        if(!$this->isModelIgnored($model)) {
            $mutation = new Mutation();
            $mutation->addRelatedModels(Helper::getCascadeRelatedModels($model));
            $mutation->fire($model, 'create');
        }
    }

    /**
     * Listen to the Model updating event.
     */
    public function updating(Model $model)
    {
        if(!$this->isModelIgnored($model)) {
            $model = (clone $model)->fill($model->getOriginal());

            $mutation = new Mutation();
            $mutation->addRelatedModels(Helper::getCascadeRelatedModels($model));
            self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)] = $mutation;
        }
    }

    /**
     * Listen to the Model updated event.
     */
    public function updated(Model $model)
    {
        if(!$this->isModelIgnored($model)) {
            $mutation = self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)];
            $mutation->addRelatedModels(Helper::getCascadeRelatedModels($model));
            $mutation->fire($model, 'update');
        }
    }

    /**
     * Listen to the Model deleting event.
     */
    public function deleting(Model $model)
    {
        if(!$this->isModelIgnored($model)) {
            $mutation = new Mutation();
            $mutation->addRelatedModels(Helper::getCascadeRelatedModels($model));
            self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)] = $mutation;
        }
    }

    /**
     * Listen to the Model deleted event.
     */
    public function deleted(Model $model)
    {
        if(!$this->isModelIgnored($model)) {
            $mutation = self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)];
            $mutation->fire($model, 'delete');
        }
    }

    protected function isModelIgnored(Model $model)
    {
        return in_array(get_class($model), self::$ignoreClasses);
    }
}
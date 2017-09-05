<?php

namespace Ifnot\LaravelVuex;

use Illuminate\Database\Eloquent\Model;

class ModelObserver
{
    public static $delayedBroadcasters = [];

    /**
     * @param array $models
     */
    public static function observe(array $models)
    {
        foreach($models as $model) {
            $model::observe(static::class);
        }
    }

    /**
     * Listen to the Model created event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function created(Model $model)
    {
        $broadcaster = new Broadcast();
        $broadcaster->addRelatedModels(Helper::getCascadeRelatedModels($model));
        $broadcaster->fire($model, 'create');
    }

    /**
     * Listen to the Model updating event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function updating(Model $model)
    {
        $model = (clone $model)->fill($model->getOriginal());

        $broadcaster = new Broadcast();
        $broadcaster->addRelatedModels(Helper::getCascadeRelatedModels($model));
        self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)] = $broadcaster;
    }

    /**
     * Listen to the Model updated event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function updated(Model $model)
    {
        $broadcaster = self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)];
        $broadcaster->addRelatedModels(Helper::getCascadeRelatedModels($model));
        $broadcaster->fire($model, 'update');
    }

    /**
     * Listen to the Model deleting event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function deleting(Model $model)
    {
        $broadcaster = new Broadcast();
        $broadcaster->addRelatedModels(Helper::getCascadeRelatedModels($model));
        self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)] = $broadcaster;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function deleted(Model $model)
    {
        $broadcaster = self::$delayedBroadcasters[Helper::getModelUniqueIndex($model)];
        $broadcaster->fire($model, 'delete');
    }
}
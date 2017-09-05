<?php

namespace Ifnot\LaravelVuex;

use Ifnot\LaravelVuex\Events\MutationEvent;
use Illuminate\Database\Eloquent\Model;

class Broadcast
{
    protected $related = [];

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param $mutation
     */
    public function fire(Model $model, $mutation)
    {
        event(new MutationEvent($model, $mutation));

        $this->forgetRelatedModel($model);
        foreach($this->getRelatedModels() as $model) {
            event(new MutationEvent($model, 'update'));
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function addRelatedModel(Model $model)
    {
        $this->related[Helper::getModelUniqueIndex($model)] = $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function forgetRelatedModel(Model $model)
    {
        if(isset($this->related[Helper::getModelUniqueIndex($model)])) {
            unset($this->related[Helper::getModelUniqueIndex($model)]);
        }
    }

    /**
     * @param array $models
     */
    public function addRelatedModels(array $models)
    {
        foreach($models as $model) {
            $this->addRelatedModel($model);
        }
    }

    /**
     * @return array
     */
    public function getRelatedModels()
    {
        return $this->related;
    }
}
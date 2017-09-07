<?php

namespace Ifnot\LaravelVuex;

use Ifnot\LaravelVuex\Events\MutationEvent;
use Ifnot\LaravelVuex\Model\Store;
use Illuminate\Database\Eloquent\Model;

class Mutation
{
    protected $related = [];

    /**
     * Fire a mutation for a model
     */
    public function fire(Model $model, string $mutation)
    {
        $this->broadcast($model, $mutation);

        $this->forgetRelatedModel($model);
        foreach($this->getRelatedModels() as $model) {
            $this->broadcast($model, 'update');
        }
    }

    /*
     * Transform a mutation into mutation events specified by the model
     * store in order to send them to the client.
     */
    protected function broadcast(Model $model, $mutation)
    {
        $store = $model->store ? new $model->store($model) : new Store($model);

        foreach($store->getBroadcasts() as $broadcast) {
            event(new MutationEvent($store->toArray($model), $broadcast, $mutation));
        }
    }

    /*
     * Add a new related model for this mutation. All related models will
     * be notified by an update mutation after this one is sent.
     */
    public function addRelatedModel(Model $model)
    {
        $this->related[Helper::getModelUniqueIndex($model)] = $model;
    }

    /**
     * Add multiple related models
     */
    public function addRelatedModels(array $models)
    {
        foreach($models as $model) {
            $this->addRelatedModel($model);
        }
    }

    /**
     * Forget a related model previously added.
     */
    public function forgetRelatedModel(Model $model)
    {
        if(isset($this->related[Helper::getModelUniqueIndex($model)])) {
            unset($this->related[Helper::getModelUniqueIndex($model)]);
        }
    }

    /**
     * Return all the related models added to this mutation
     */
    public function getRelatedModels(): array
    {
        return $this->related;
    }
}
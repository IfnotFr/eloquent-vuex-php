<?php

namespace Ifnot\LaravelVuex\Vuex;

use Ifnot\LaravelVuex\Helper;
use Ifnot\LaravelVuex\Vuex\States\State;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class Store
{
    protected $class;

    public function __construct(Model $model)
    {
        $this->class = get_class($model);
    }

    /**
     * Return the related models witch should be updated when this model is updated / deleted
     */
    public function getCascadeRelations(): array
    {
        return [];
    }

    /**
     * Return the frontend namespace of the store
     */
    public function getNamespace(): string
    {
        $baseClassName = basename(str_replace('\\', '/', $this->class));
        $namespace = str_plural(snake_case($baseClassName));

        return $namespace;
    }

    /**
     * Transform the model object to array in order to be serialized on the broadcast event.
     */
    public function toArray(Model $model): array
    {
        return $model->toArray();
    }

    /**
     * Get the frontend states to be sync by this Store.
     */
    public function getStates(Model $model): array
    {
        return [
            new State($this, 'all', new Channel('public')),
        ];
    }

    /**
     * Forward the create / update / delete calls to all the States of the Store
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, ['create', 'update', 'delete'])) {
            list($model, $meta) = $arguments;
            $noRelations = isset($arguments[2]) ? $arguments[2] : false;

            foreach ($this->getStates($model) as $state) {
                $state->$name($model, $meta);
            }
            if(! $noRelations) {
                foreach ($this->getRelatedStates($model) as $stateInfos) {
                    $stateInfos['state']->update($stateInfos['model']);
                }
            }
        }
    }

    protected function getRelatedStates(Model $model): array
    {
        $original = (clone $model)->fill($model->getOriginal());
        $states = [];

        foreach (array_merge(Helper::getCascadeRelatedModels($model), Helper::getCascadeRelatedModels($original)) as $relatedModel) {
            $store = $relatedModel->store ? new $relatedModel->store($relatedModel) : new Store($relatedModel);
            foreach($store->getStates($relatedModel) as $state) {
                $states[] = [
                    'state' => $state,
                    'model' => $relatedModel
                ];
            }
        }

        return $states;
    }
}
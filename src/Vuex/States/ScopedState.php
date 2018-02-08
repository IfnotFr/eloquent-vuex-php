<?php

namespace Ifnot\EloquentVuex\Vuex\States;

use Ifnot\EloquentVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;

class ScopedState extends State
{
    protected $scope;

    public function __construct(Store $store, $name, $channel, callable $scope)
    {
        parent::__construct($store, $name, $channel);

        $this->scope = $scope;
    }

    public function create(Model $model, array $meta = [])
    {
        if($this->scope($model)) {
            $this->emit($model, 'create', $this->channel, $meta);
        }
    }

    public function update(Model $model, array $meta = [])
    {
        $original = (new $model)->fill($model->getOriginal());

        if (! $this->scope($original) && $this->scope($model)) {
            // If the model did not pass the scope before the modification but
            // now pass after it, create the model into the state
            $this->emit($model, 'create', $this->channel, $meta);
        } elseif ($this->scope($original) && !$this->scope($model)) {
            // If the model passed the scope before the modification but now does
            // not pass, delete the model into the store
            $this->emit($model, 'delete', $this->channel, $meta);
        } elseif ($this->scope($model)) {
            // If the model just pass like usual, broadcast a simple update
            $this->emit($model, 'update', $this->channel, $meta);
        }
    }

    public function delete(Model $model, array $meta = [])
    {
        if($this->scope($model)) {
            $this->emit($model, 'delete', $this->channel, $meta);
        }
    }
}
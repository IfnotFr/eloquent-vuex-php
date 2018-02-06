<?php

namespace Ifnot\LaravelVuex\Vuex\States;

use Ifnot\LaravelVuex\Events\MutationEvent;
use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class State
{
    protected $store;
    protected $name;
    protected $channel;

    public function __construct(Store $store, string $name, $channel)
    {
        $this->store = $store;
        $this->name = $name;
        $this->channel = $channel;
    }

    public function getChannel(Model $model, array $meta = [])
    {
        if(is_callable($this->channel)) {
            return call_user_func_array($this->channel, [$model, $meta]);
        } else {
            return $this->channel;
        }
    }

    public function create(Model $model, array $meta = [])
    {
        $this->emit($model, 'create', $this->getChannel($model, $meta), $meta);
    }

    public function update(Model $model, array $meta = [])
    {
        $this->emit($model, 'update', $this->getChannel($model, $meta), $meta);
    }

    public function delete(Model $model, array $meta = [])
    {
        $this->emit($model, 'delete', $this->getChannel($model, $meta),  $meta);
    }

    public function __call($name, $args)
    {
        $model = Arr::get($args, 0);
        $meta = Arr::get($args, 1, []);

        $this->emit($model, $name, $this->getChannel($model, $meta), $meta);
    }

    public function emit(Model $model, string $event, $channel, array $meta = [])
    {
        if($channel !== false) {
            event(new MutationEvent($this->store->getNamespace(), $this->name, $this->store->toArray($model), $event, $meta, $channel));
        }
    }
}
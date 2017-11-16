<?php

namespace Ifnot\LaravelVuex\Vuex\States;

use Ifnot\LaravelVuex\Events\BroadcastEvent;
use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class State
{
    protected $store;
    protected $name;
    protected $channel;

    public function __construct(Store $store, string $name, Channel $channel)
    {
        $this->store = $store;
        $this->name = $name;
        $this->channel = $channel;
    }

    public function create(Model $model, array $meta = [])
    {
        $this->emit($model, 'create', $this->channel, $meta);
    }

    public function update(Model $model, array $meta = [])
    {
        $this->emit($model, 'update', $this->channel, $meta);
    }

    public function delete(Model $model, array $meta = [])
    {
        $this->emit($model, 'delete', $this->channel,  $meta);
    }

    public function __call($name, $args)
    {
        $this->emit(Arr::get($args, 0), $name, $this->channel, Arr::get($args, 1, []));
    }

    public function emit(Model $model, string $event, Channel $channel, array $meta = [])
    {
        event(new BroadcastEvent($this->store->getNamespace(), $this->name, $this->store->toArray($model), $event, $meta, $channel));
    }
}
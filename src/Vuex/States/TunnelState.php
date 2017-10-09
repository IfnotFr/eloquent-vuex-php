<?php

namespace Ifnot\LaravelVuex\Vuex\States;

use Ifnot\LaravelVuex\Vuex\Store;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class TunnelState extends State
{
    protected $channelHandler;

    public function __construct(Store $store, $name, callable $channelHandler)
    {
        parent::__construct($store, $name, new Channel('global'));

        $this->channelHandler = $channelHandler;
    }

    protected function getChannel(Model $model)
    {
        $channelHandler = $this->channelHandler;
        return $channelHandler($model);
    }

    public function create(Model $model, array $meta = [])
    {
        $channel = $this->getChannel($model);

        if ($channel !== false) {
            $this->emit($model, 'create', $channel, $meta);
        }
    }

    public function update(Model $model, array $meta = [])
    {
        $original = (new $model)->fill($model->getOriginal());

        $originalChannel = $this->getChannel($original);
        $channel = $this->getChannel($model);

        // If the model register a channel modification
        if ($originalChannel != $channel) {

            // If there is a original channel for this model, delete it
            if($originalChannel !== false) {
                $this->emit($model, 'delete', $originalChannel, $meta);
            }

            // If there is a target channel for this model, create it
            if($channel !== false) {
                $this->emit($model, 'create', $channel, $meta);
            }
        } elseif($channel !== false) {
            // If there is a channel, just update it
            $this->emit($model, 'update', $channel, $meta);
        }
    }

    public function delete(Model $model, array $meta = [])
    {
        $channel = $this->getChannel($model);

        if ($channel !== false) {
            $this->emit($model, 'delete', $channel, $meta);
        }
    }
}
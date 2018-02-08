<?php

namespace Ifnot\EloquentVuex\Vuex\States;

use Ifnot\EloquentVuex\Vuex\Store;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class TunnelState extends State
{
    protected $channelHandler;

    public function update(Model $model, array $meta = [])
    {
        $original = (new $model)->fill($model->getOriginal());

        $originalChannel = $this->getChannel($original, []);
        $channel = $this->getChannel($model, []);

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
}
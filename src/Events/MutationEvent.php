<?php

namespace Ifnot\LaravelVuex\Events;

use Ifnot\LaravelVuex\Model\IsStore;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MutationEvent implements ShouldBroadcast
{
    private $model;
    private $store;
    private $mutation;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param $mutation
     * @throws \Exception
     */
    public function __construct(Model $model, $mutation)
    {
        if(!method_exists($model, 'getStore')) {
            throw new \Exception("Could not broadcast vuex mutation event for model " . get_class($model) . ", the model does not have the " . IsStore::class . " trait.");
        }

        $this->model = $model;
        $this->store = $model->getStore();
        $this->mutation = $mutation;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'laravel.vuex:mutation';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'namespace' => $this->store->getNamespace($this->model),
            'mutation' => $this->mutation,
            'state' => $this->store->getState(),
            'payload' => $this->store->toArray($this->model),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return $this->store->broadcastOn();
    }
}
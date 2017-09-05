<?php

namespace Ifnot\LaravelVuex\Model;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class Store
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Return the related models witch should be updated when this model is updated / deleted
     *
     * @return array
     */
    public function getCascadeRelations()
    {
        return [];
    }

    /**
     * Get the namespace of the vuex store
     *
     * @return string
     */
    public function getNamespace()
    {
        return str_plural(snake_case(class_basename($this->model)));
    }

    /**
     * Get the vuex store state where we should bring the broadcast.
     *
     * @return string|array
     */
    public function getState()
    {
        return 'all';
    }

    /**
     * Transform the model object to array in order to be serialized on the broadcast event.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function toArray(Model $model)
    {
        return $model->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('public');
    }

    /**
     * Determine if this event should broadcast.
     *
     * @return bool
     */
    public function broadcastWhen()
    {
        return true;
    }
}
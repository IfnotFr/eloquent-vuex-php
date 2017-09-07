<?php

namespace Ifnot\LaravelVuex\Model;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;

class Store
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Return the related models witch should be updated when this model is updated / deleted
     */
    public function getCascadeRelations(): array
    {
        return [];
    }

    /**
     * Transform the model object to array in order to be serialized on the broadcast event.
     */
    public function toArray(): array
    {
        return $this->model->toArray();
    }

    /**
     * Get the different broadcasts to do for this model.
     * You can do multiple broadcasts for a single model event.
     */
    public function getBroadcasts(): array
    {
        return [
            [
                'namespace' => str_plural(snake_case(class_basename($this->model))),
                'state' => 'all',
                'channel' => new Channel('public'),
                'events' => ['create', 'update', 'delete'],
            ],
        ];
    }
}
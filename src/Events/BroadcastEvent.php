<?php

namespace Ifnot\LaravelVuex\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastEvent implements ShouldBroadcast
{
    private $namespace;
    private $state;

    private $payload;
    private $mutation;

    private $meta;
    private $channel;

    /**
     * Create a new MutationEvent instance.
     */
    public function __construct(string $namespace, string $state, array $payload, string $mutation, array $meta = [], Channel $channel = null)
    {
        $this->namespace = $namespace;
        $this->state = $state;

        $this->payload = $payload;
        $this->mutation = $mutation;

        $this->meta = $meta;
        $this->channel = $channel ?? new Channel('global');
    }

    /**
     * Get the event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'laravel.vuex:mutation';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'vuex' => [
                'namespace' => $this->namespace,
                'state' => $this->state,
                'mutation' => $this->mutation,
            ],
            'meta' => $this->meta,
            'payload' => $this->payload,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return $this->channel;
    }
}
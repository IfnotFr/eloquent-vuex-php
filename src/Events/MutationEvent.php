<?php

namespace Ifnot\LaravelVuex\Events;

use Ifnot\LaravelVuex\Model\IsStore;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MutationEvent implements ShouldBroadcast
{
    private $payload;
    private $broadcast;
    private $mutation;

    /**
     * Create a new MutationEvent instance.
     */
    public function __construct(array $payload, array $broadcast, string $mutation)
    {
        $this->payload = $payload;
        $this->broadcast = $broadcast;
        $this->mutation = $mutation;
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
                'namespace' => $this->broadcast['namespace'],
                'state' => $this->broadcast['state'],
                'mutation' => $this->mutation,
            ],
            'payload' => $this->payload,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return $this->broadcast['channel'];
    }

    /**
     * Determine if this event should broadcast or not based on the event whitelist
     */
    public function broadcastWhen(): bool
    {
        // If there is no whitelist for mutation names
        if(!isset($this->broadcast['events'])) {
            return true;
        }

        // If the mutation name is whitelisted on the broadcast events
        return in_array($this->mutation, $this->broadcast['events']);
    }
}
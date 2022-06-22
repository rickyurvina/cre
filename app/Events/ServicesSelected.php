<?php

namespace App\Events;

use App\Models\Projects\Activities\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServicesSelected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $servicesSelect;
    public $result;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($servicesSelect, Task $result)
    {
        //
        $this->servicesSelect = $servicesSelect;
        $this->result=$result;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

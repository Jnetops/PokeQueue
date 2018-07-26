<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class transferAdmin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $users;

    public function __construct($type, $users)
    {
        $this->type = $type;
        $this->users = $users;
    }

    public function broadcastOn()
    {
        if ($this->type['type'] == 'public')
        {
          return [$this->type['subType'] . '-channel-' . $this->type['id']];
        }
        elseif ($this->type['type'] == 'private')
        {
          return ['private-' . $this->type['subType'] . '-channel-' . $this->type['id']];
        }
    }
}

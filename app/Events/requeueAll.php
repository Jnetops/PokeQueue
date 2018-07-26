<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class requeueAll implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    
    public function __construct($type)
    {
      $this->type = $type;
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

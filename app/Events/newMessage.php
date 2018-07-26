<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class newMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $text;
    public $type;

    public function __construct($text, $type)
    {
        $this->text = $text;
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

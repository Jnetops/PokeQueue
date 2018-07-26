<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Events;
use Auth;

class Events_Chat extends Model
{
  protected $fillable = ['event-id', 'user_id', 'trainer_name', 'trainer_team', 'comment'];
  protected $table = 'event_chat';

  protected $casts = [
      'users' => 'array',
  ];

  public function submit_event_chat($request)
  {
    $checkEvent = Events::where('id', $request->input('event-id'))->first();

    if (in_array(Auth::user()->username, $checkEvent->users))
    {
      $addChat = Events_Chat::create([
          'user_id' => Auth::user()->id,
          'trainer_name' => Auth::user()->trainer_name,
          'trainer_team' => Auth::user()->trainer_team,
          'event-id' => $request->input('event-id'),
          'comment' => $request->input('comment'),
      ]);
      return array('Success' => 'True', 'Chat' => $addChat, 'Event' => $checkEvent);
    }
    else {
      return array('Success' => 'False', 'Error' => 'You do not belong to this event');
    }

  }
}

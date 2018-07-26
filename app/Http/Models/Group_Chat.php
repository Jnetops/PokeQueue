<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Group;
use Auth;

class Group_Chat extends Model
{
    protected $fillable = ['group-id', 'user_id', 'trainer_name', 'trainer_team', 'comment'];
    protected $table = 'group_chat';

    protected $casts = [
        'users' => 'array',
    ];

    public function submit_group_chat($request)
    {
      $checkGroup = Group::where('id', $request->input('group-id'))->first();

      if (in_array(Auth::user()->username, $checkGroup->users))
      {
        $addChat = Group_Chat::create([
            'user_id' => Auth::user()->id,
            'trainer_name' => Auth::user()->trainer_name,
            'trainer_team' => Auth::user()->trainer_team,
            'group-id' => $request->input('group-id'),
            'comment' => $request->input('comment'),
        ]);
        return array('Success' => 'True', 'Chat' => $addChat, 'Group' => $checkGroup);
      }
      else {
        return array('Success' => 'False', 'Error' => 'You do not belong to this group');
      }

    }
}

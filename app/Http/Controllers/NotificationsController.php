<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Group;
use App\Http\Models\Events;
use Auth;
use App;
use Pusher;
use View;

class NotificationsController extends Controller
{
    protected $casts = [
        'users' => 'array',
    ];

    public $key;
    public $secret;
    public $app_id;

    public function __construct()
    {
        $this->key = env('PUSHER_APP_KEY');
        $this->secret = env('PUSHER_APP_SECRET');
        $this->app_id = env('PUSHER_APP_ID');
    }

    public function delete($id) {
        $user = Auth::user();
        $notification = $user->notifications()->where('id',$id)->first();
        if ($notification)
        {
            $notification->delete();
            return array('Success' => 'True');
        }
        else {
            return array('Success' => 'False');
        }
    }

    public function deleteAll() {
        $user = Auth::user();
        $notification = $user->unreadNotifications;
        if ($notification)
        {
            foreach ($notification as $not)
            {
                $not->delete();
            }
            return 'true';
        }
        else {
            return 'false';
        }
    }

    public function all(Request $request) {
        $notifications = Auth::user()->unreadNotifications;
        return View::make('all_notifications', ['notifications' => $notifications]);
    }

    public function groupChatAuth(Request $request, $groupID) {

      // check if user belongs to group - if so, carry out below code, otherwise return false
      $existing = Group::where('id', $groupID)->first(['admin', 'users']);
      if ($existing)
      {
        if (Auth::user()->trainer_name == $existing->admin || in_array(Auth::user()->trainer_name, $existing->users))
        {
          $socketId = $request->input('socket_id');
          $channelName = $request->input('channel_name');
          $pusher = new Pusher($this->key, $this->secret, $this->app_id);

          $auth = $pusher->socket_auth($channelName, $socketId);
          return $auth;
        }
      }
      return false;
    }

    public function eventChatAuth(Request $request, $eventID) {

      // check if user belongs to group - if so, carry out below code, otherwise return false
      $existing = Events::where('id', $eventID)->first(['admin', 'users']);
      if ($existing)
      {
        if (Auth::user()->trainer_name == $existing->admin || in_array(Auth::user()->trainer_name, $existing->users))
        {
          $socketId = $request->input('socket_id');
          $channelName = $request->input('channel_name');
          $pusher = new Pusher($this->key, $this->secret, $this->app_id);

          $auth = $pusher->socket_auth($channelName, $socketId);
          return $auth;
        }
      }
      return false;
    }

    public function notificationAuth(Request $request) {
      $socketId = $request->input('socket_id');
      $channelName = $request->input('channel_name');

      if (strpos($channelName, Auth::user()->trainer_name) !== false) {
          $pusher = new Pusher($this->key, $this->secret, $this->app_id);
          $auth = $pusher->socket_auth($channelName, $socketId);
          return $auth;
      }
      else {
          return false;
      }
    }
}

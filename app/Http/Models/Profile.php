<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\User;
use Log;
use App\Http\Models\Friends;
use App\Traits\GoogleApi;
use App\Traits\CacheReturn;

class Profile extends Model
{

    use GoogleApi;
    use CacheReturn;
    protected $fillable = ['trainer_team', 'trainer_level', 'age', 'sex', 'location', 'lat', 'lon', 'timezone'];
    protected $table = 'profile';
    protected $casts = [
      'users' => 'array',
    ];

    public function getProfile($request) {

      $profile = User::where('id', Auth::user()->id)->first();
      $friends = new Friends();
      $getFriends = $friends->gather_friends(Auth::user()->trainer_name);
      $friendsArray = array();
      foreach ($getFriends as $friend)
      {
        if ($friend->received_uid != Auth::user()->trainer_name && $friend->status == "accepted")
        {
          array_push($friendsArray, $friend->received_uid);
        }
        elseif ($friend->received_uid == Auth::user()->trainer_name && $friend->status == "accepted") {
          array_push($friendsArray, $friend->request_uid);
        }
      }


      $friendInfo = User::whereIn('trainer_name', $friendsArray)->get();

      // if (Cache::has('groups'))
      // {
      //   $groups = Cache::get('groups');
      // } else {
      //     $groups = Group::get();
      //     Cache::store('redis')->put('groups', $groups, 30);
      //     Log::info('group stored');
      // }

      $groups = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);

      $events = $this->gather('events', 'return \App\Http\Models\Events::get();', 30);


      $groupFound = '';
      $eventArray = array();

      foreach ($groups as $group)
      {
        if (Auth::user()->trainer_name == $group->admin || in_array(Auth::user()->trainer_name, $group->users))
        {
          $groupFound = $group;
        }
      }

      foreach ($events as $event)
      {
        if (Auth::user()->trainer_name == $event->admin || in_array(Auth::user()->trainer_name, $event->users))
        {
          array_push($eventArray, $event);
        }
      }

      // get friends activity stream to display to user


      return array('friends' => $friendInfo, 'profile' => $profile, 'group' => $groupFound, 'events' => $eventArray);
      //return $profile;

    }

    public function getUser($request, $userID) {

      $profile = User::where('trainer_name', $userID)->first();
      $friends = new Friends();
      $getFriends = $friends->gather_friends($userID);

      $friendsArray = array();
      $isFriends = false;
      $hasRequested = false;
      foreach ($getFriends as $friend)
      {
        if ($friend->status == 'accepted')
        {
          if (ucfirst($friend->request_uid) == ucfirst($userID))
          {
            if (ucfirst($friend->received_uid) == ucfirst(Auth::user()->trainer_name))
            {
              $isFriends = true;
              array_push($friendsArray, ucfirst($friend->received_uid));
            }
          }
          elseif (ucfirst($friend->received_uid) == ucfirst($userID)) {
            if (ucfirst($friend->request_uid) == ucfirst(Auth::user()->trainer_name))
            {
              $isFriends = true;
              array_push($friendsArray, ucfirst($friend->request_uid));
            }
          }
        }
        else {
          if (ucfirst($friend->received_uid) == ucfirst(Auth::user()->trainer_name) && $userID != Auth::user()->trainer_name)
          {
            $hasRequested = true;
          }
          elseif (ucfirst($friend->request_uid) == ucfirst(Auth::user()->trainer_name) && $userID != Auth::user()->trainer_name) {
            $hasRequested = true;
          }
        }
      }

      $friendInfo = User::whereIn('trainer_name', $friendsArray)->get();

      $groups = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);

      $events = $this->gather('events', 'return \App\Http\Models\Events::get();', 30);

      $groupFound = '';
      $eventArray = array();

      foreach ($groups as $group)
      {
        if ($userID == $group->admin || in_array($userID, $group->users))
        {
          $groupFound = $group;
        }
      }

      foreach ($events as $event)
      {
        if ($userID == $event->admin || in_array($userID, $event->users))
        {
          array_push($eventArray, $event);
        }
      }

      // get friends activity stream to display to user


      return array('friends' => $friendInfo, 'profile' => $profile, 'group' => $groupFound, 'events' => $eventArray, 'isFriends' => $isFriends, 'hasRequested' => $hasRequested);
      //return $profile;
    }

    public function updateLocation($request) {

      if ($request->has('lat') && $request->has('lon')) {
        $location = $request->input('lat') . ', ' . $request->input('lon');

        $locationCheck = $this->GoogleLocation($location, 'latlng');
        if ($locationCheck['Success'] == 'False')
        {
          return array('Success' => 'False', 'Error' => 'Unable to gather location');
        }
        else {
          foreach ($locationCheck['results'] as $one)
          {
            foreach ($one['address_components'] as $two)
            {
              if (array_key_exists('types', $two))
              {
                if (in_array('locality', $two['types']))
                {
                  $city = $two['long_name'];
                }
                elseif (in_array('administrative_area_level_1', $two['types']))
                {
                  $state = $two['short_name'];
                }
              }
            }
          }
        }
      }

      elseif ($request->has('city') && $request->has('state')) {
        $location = ucfirst($request->input('city')) . ', ' . $request->input('state');

        if ($request->has('address'))
        {
          $address = $request->input('address') . ' ' . $request->input('city') . ', ' . $request->input('state');
        }
        else {
          $address = $request->input('city') . ', ' . $request->input('state');
        }

        $locationCheck = $this->GoogleLocation($address, 'address');
        if ($locationCheck['Success'] == 'False')
        {
          return array('Success' => 'False', 'Error' => 'Unable to gather location');
        }
        else {
          $request->request->add(['location' => $location, 'lat' => $locationCheck['lat'], 'lon' => $locationCheck['lon']]);
        }
      }
      elseif ($request->has('city') && !$request->has('state')) {
        return 'needs city and state to update location';
      }
      elseif (!$request->has('city') && $request->has('state')) {
        return 'needs city and state to update location';
      }

      if (isset($city) && isset($state))
      {
        $newLoc = $city . ', ' . $state;
        $request->request->add(['location' => $newLoc]);
        $updateProfile = User::where('id', Auth::user()->id)->update($request->except(['state', 'address', 'city', '_token', 'id', 'username', 'email', 'trainer_name']));
        return $updateProfile;
      }

      $updateProfile = User::where('id', Auth::user()->id)->update($request->except(['state', 'address', 'city', '_token', 'id', 'username', 'email', 'trainer_name']));
      return $updateProfile;

    }

    public function updateLevel($request) {
      $updateProfile = User::where('id', Auth::user()->id)->update($request->except(['state', 'city', '_token', 'id', 'username', 'email', 'trainer_name']));
      return $updateProfile;
    }

    public function updateTeam($request) {
      $updateProfile = User::where('id', Auth::user()->id)->update($request->except(['state', 'city', '_token', 'id', 'username', 'email', 'trainer_name']));
      if ($updateProfile)
      {
        $updateGroupChat = Group_Chat::where('trainer_name', Auth::user()->trainer_name)->update(['trainer_team' => $request->input('trainer_team')]);
        $updateEventChat = Events_Chat::where('trainer_name', Auth::user()->trainer_name)->update(['trainer_team' => $request->input('trainer_team')]);
      }
      return $updateProfile;
    }

    public function updatePassword($request) {
      if ($request->has('password')) {
        $password = bcrypt($request['password']);
        $updatePass = User::where('trainer_name', Auth::user()->trainer_name)->update(['password' => $password]);
        if ($updatePass)
        {
          return array('Success' => 'True');
        }
        return array('Success' => 'False');
      }
    }

    public function updateTrainerName($request)
    {
      $trainer = Auth::user()->trainer_name;
      $newTrainer = $request->input('trainer-name');

      $profileGather = User::where('trainer_name', $trainer)->first();
      $today = new \DateTime(date("Y-m-d"));
      $recordedDate = new \DateTime($profileGather->trainer_updated);
      if ($today > $recordedDate->modify('+30 day'))
      {
        $profile = User::where('trainer_name', $trainer)->update(['trainer_name' => $newTrainer]);

        $events = $this->gather('events', 'return \App\Http\Models\Events::get();', 30);

  		  $events_chat = Events_Chat::where('trainer_name', $trainer)->update(['trainer_name' => $newTrainer]); // trainer_name only
  		  $events_invite = Events_Invite::where('invitee-id', $trainer)->orWhere('inviter-id', $trainer)->get(); // trainer_name but in invitee-id or inviter-id
  		  foreach ($events as $event)
  		  {
    			if ($trainer == $event->admin)
    			{
    			  $eventUpdate = Events::where('id', $event->id)->update(['admin' => $newTrainer]);
            $this->updateCache('events');
    			}
    			elseif (in_array($trainer, $event->users)) {
    			  $updatedUsers = str_replace($trainer, $newTrainer, $event->users);
    			  $eventUpdate = Events::where('id', $event->id)->update(['users' => $updatedUsers]);
            $this->updateCache('events');
    			}
  		  }
  		  foreach ($events_invite as $invite)
  		  {
    			if($invite['invitee-id'] == $trainer)
    			{
    			  $inviteUpdate = Events_Invite::where('id', $invite->id)->update(['invitee-id' => $newTrainer]);
    			}
    			elseif ($invite['inviter-id'] == $trainer)
    			{
    			  $inviteUpdate = Events_Invite::where('id', $invite->id)->update(['inviter-id' => $newTrainer]);
    			}
  		  }

        $events = $this->gather('events', 'return \App\Http\Models\Events::get();', 30);

		  $groups_chat = Group_Chat::where('trainer_name', $trainer)->update(['trainer_name' => $newTrainer]); // trainer_name only
		  $groups_invite = Group_Invite::where('invitee-id', $trainer)->orWhere('inviter-id', $trainer)->get(); // 'inviter-id', 'invitee-id'
		  foreach ($groups as $group)
		  {
  			if ($trainer == $group->admin)
  			{
          $updatedUsers = str_replace($trainer, $newTrainer, $group->users);
  			  $groupUpdate = Group::where('id', $group->id)->update(['admin' => $newTrainer, 'users' => [$updatedUsers]]);
          $this->updateCache('groups');
  			}
  			elseif (in_array($trainer, $group->users)) {
  			  $updatedUsers = str_replace($trainer, $newTrainer, $group->users);
  			  $groupUpdate = Group::where('id', $group->id)->update(['users' => $updatedUsers]);
          $this->updateCache('groups');
  			}
		  }

		  foreach ($groups_invite as $invite)
		  {
			if($invite['invitee-id'] == $trainer)
			{
			  $inviteUpdate = Group_Invite::where('id', $invite->id)->update(['invitee-id' => $newTrainer]);
			}
			elseif ($invite['inviter-id'] == $trainer)
			{
			  $inviteUpdate = Group_Invite::where('id', $invite->id)->update(['inviter-id' => $newTrainer]);
			}
		  }

		  $friends = Friends::where('received_uid', $trainer)->orWhere('request_uid', $trainer)->get(); // request_uid', 'received_uid
		  foreach ($friends as $friend)
		  {
			if($friend->request_uid == $trainer)
			{
			  $friendUpdate = Friends::where('id', $friend->id)->update(['request_uid' => $newTrainer]);
			}
			elseif ($friend->received_uid == $trainer)
			{
			  $friendUpdate = Friends::where('id', $friend->id)->update(['received_uid' => $newTrainer]);
			}
		  }
          $profileUpdate = User::where('trainer_name', $trainer)->update(['trainer_name' => $newTrainer, 'trainer_updated' => date('Y-m-d')]);
      }
      else {
        return 'Failed to update trainer name';
      }
    }
}

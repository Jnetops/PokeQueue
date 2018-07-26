<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Traits\Distance;
use App\User;
use Carbon\Carbon;
use App\Http\Models\RaidTracker;
use App\Traits\GoogleApi;
use App\Traits\SendNotifications;
use App\Traits\CacheReturn;

class Group extends Model
{
  use SendNotifications;
  use CacheReturn;
  use GoogleApi;
  use Distance;
  protected $fillable = ['type', 'subType1', 'subType2', 'description', 'users', 'count', 'admin', 'status', 'location', 'address', 'lat', 'lon', 'timezone'];
  protected $table = 'group';
  // serialize and unserialize JSON from TEXT field in DB
  // used to store userid's that are in a particular group
  protected $casts = [
      'users' => 'array',
  ];

  public function Create_Group($request)
  {
    $existingGroup = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);
    foreach ($existingGroup as $existing)
    {
      if (Auth::user()->trainer_name == $existing->admin || in_array(Auth::user()->trainer_name, $existing->users))
      {
        return array('Success' => 'False', 'Error' => 'User already created group');
      }
    }
    $users = array(Auth::user()->trainer_name);
    $location = ucfirst($request->input('city')) . ', ' . $request->input('state');
    // get lat lon from addy
    $address = $request->input('address') . ', ' . ucfirst($request->input('city')) . ', ' . $request->input('state');

    $locationCheck = $this->GoogleLocation($address, 'address');
    if ($locationCheck['Success'] == 'False')
    {
      return array('Success' => 'False', 'Error' => 'Unable to gather location');
    }
    else {
      $request->request->add(['lat' => $locationCheck['lat'], 'lon' => $locationCheck['lon']]);

      $timeZoneCheck = $this->Timezone($locationCheck['lat'], $locationCheck['lon']);
      if ($timeZoneCheck['Success'] == 'False')
      {
        return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
      }
      else {
        $request->request->add(['timezone' => $timeZoneCheck['timezone']]);
      }
    }

    $request->request->add(['users' => $users, 'location' => $location, 'admin' => Auth::user()->trainer_name, 'status' => 'queued']);
    $groupCreate = Group::create($request->all());
    $this->updateCache('groups');

    if ($groupCreate)
    {
      $friends = Friends::where([['request_uid', $groupCreate->admin], ['status', 'accepted']])->orWhere([['received_uid', $groupCreate->admin], ['status', 'accepted']])->get();

      $friendArray = [];
      foreach ($friends as $friend)
      {
        if (ucfirst($friend->received_uid) != ucfirst(Auth::user()->trainer_name))
        {
          $friendArray[] = ucfirst($friend->received_uid);
        }
        elseif (ucfirst($friend->request_uid) != ucfirst(Auth::user()->trainer_name)) {
          $friendArray[] = ucfirst($friend->request_uid);
        }
      }

      $users = User::whereIn('trainer_name', $friendArray)->get();

      $this->createGroupFunc($users, $groupCreate);
    }

    return array('Success' => 'True', 'Group' => $groupCreate);
  }

  public function Create_Raid_Group($request)
  {
    $existingGroup = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);
    foreach ($existingGroup as $existing)
    {
      if (Auth::user()->trainer_name == $existing->admin || in_array(Auth::user()->trainer_name, $existing->users))
      {
        return array('Success' => 'False', 'Error' => 'User Is Already In A Group');
      }
    }

    $raidID = $request->input('raid-id');

    $getRaid = RaidTracker::where('id', $raidID)->first();

    if ($getRaid)
    {
      if ($request->has('exists') && $request->input('exists') == 'ignore')
      {
        $timeZoneCheck = $this->Timezone($getRaid->gym_lat, $getRaid->gym_lon);
        if ($timeZoneCheck['Success'] == 'False')
        {
          return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
        }
        else {
          $timezone = $timeZoneCheck['timezone'];
        }

        $groupCreate = Group::create(
          ['type' => 4,
          'subType1' => $getRaid->star_level,
          'subType2' => $getRaid->pokemon_id,
          'address' => $getRaid->address,
          'location' => $getRaid->location,
          'count' => 20,
          'status' => 'queued',
          'admin' => Auth::user()->trainer_name,
          'users' => array(Auth::user()->trainer_name),
          'description' => $getRaid->star_level . ' Star ' . $getRaid->pokemon_name . ' Raid',
          'lat' => $getRaid->gym_lat,
          'lon' => $getRaid->gym_lon,
          'timezone' => $timezone
          ]
        );
      }
      else {
         $checkGroup = Group::where('type', 4)
                         ->where('subType1', $getRaid->star_level)
                         ->where('subType2', $getRaid->pokemon_id)
                         ->where('address', $getRaid->address)
                         ->where('location', $getRaid->location)
                         ->first();

        if ($checkGroup)
        {
          return array('Exists' => 'true', 'raidID' => $raidID, 'groupID' => $checkGroup->id);
        }
        else {
          $timeZoneCheck = $this->Timezone($getRaid->gym_lat, $getRaid->gym_lon);
          if ($timeZoneCheck['Success'] == 'False')
          {
            return array('Success' => 'False', 'Error' => 'Unable to gather timezone');
          }
          else {
            $timezone = $timeZoneCheck['timezone'];
          }
          // group doesn't exist, create group
          $groupCreate = Group::create(
            ['type' => 4,
            'subType1' => $getRaid->star_level,
            'subType2' => $getRaid->pokemon_id,
            'address' => $getRaid->address,
            'location' => $getRaid->location,
            'count' => 20,
            'status' => 'queued',
            'admin' => Auth::user()->trainer_name,
            'users' => array(Auth::user()->trainer_name),
            'description' => $getRaid->star_level . ' Star ' . $getRaid->pokemon_name . ' Raid',
            'lat' => $getRaid->gym_lat,
            'lon' => $getRaid->gym_lon,
            'timezone' => $timezone
            ]
          );
        }
      }

      if ($groupCreate)
      {
        $this->updateCache('groups');
        $friends = Friends::where([['request_uid', $groupCreate->admin], ['status', 'accepted']])->orWhere([['received_uid', $groupCreate->admin], ['status', 'accepted']])->get();

        $friendArray = [];
        foreach ($friends as $friend)
        {
          if (ucfirst($friend->received_uid) != ucfirst(Auth::user()->trainer_name))
          {
            $friendArray[] = ucfirst($friend->received_uid);
          }
          elseif (ucfirst($friend->request_uid) != ucfirst(Auth::user()->trainer_name)) {
            $friendArray[] = ucfirst($friend->request_uid);
          }
        }

        $users = User::whereIn('trainer_name', $friendArray)->get();


        $this->createRaidGroup($users, $groupCreate);
        return array('Success' => 'True', 'Group' => $groupCreate);
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Raid Not Found');
    }
  }

  public function Disband_Group($request)
  {
    $userid = Auth::user()->trainer_name;
    $checkGroup = Group::where([['admin', $userid], ['id', $request->input('group-id')]])->first();
    if ($checkGroup)
    {
      $userArray = [];
      foreach ($checkGroup->users as $user)
      {
          if ($user != Auth::user()->trainer_name)
          {
            $userArray[] = $user;
          }
      }
      $userSearch = User::whereIn('trainer_name', $userArray)->get();

      $this->GroupDisbanded($userSearch, $checkGroup);


      $deleteGroup = Group::where('id', $request->input('group-id'))->delete();
      Group_Invite::where('group-id', $request->input('group-id'))->delete();
      Group_Chat::where('group-id', $request->input('group-id'))->delete();
      $this->updateCache('groups');
      return array('Success' => 'True', 'Group' => $deleteGroup);
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to disband group');
    }
  }

  public function Leave_Group($request)
  {
    $checkGroup = Group::where('id', $request->input('group-id'))->first();
    if ($checkGroup)
    {
      if (Auth::user()->trainer_name != $checkGroup->admin)
      {
        if (in_array(Auth::user()->trainer_name, $checkGroup->users))
        {
          $users = $checkGroup->users;
          $k = array_search(Auth::user()->trainer_name, $users);
          unset($users[$k]);
          Group::find($request->input('group-id'))->update(['users' => $users]);
          $this->updateCache('groups');
          $getUsers = Group::where('id', $request->input('group-id'))->first(['users', 'admin']);
          $userData = User::whereIn('trainer_name', $getUsers->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
          return array('Success' => 'True', 'Users' => $userData, 'Admin' => $getUsers->admin);
        }
        else {
          return array('Success' => 'False', 'Validation' => 'True', 'Error' => 'User Not In Group');
        }
      }
      else {
        return array('Success' => 'False', 'Validation' => 'True', 'Error' => 'Admin Cant Leave Group');
      }
    }
    else {
      return array('Success' => 'False', 'Validation' => 'True', 'Error' => 'No Group Found');
    }
  }

  public function Finalize_Group($request)
  {
    $userid = Auth::user()->trainer_name;
    $checkGroup = Group::where([['admin', $userid], ['id', $request->input('group-id')]])->first();
    if ($checkGroup)
    {
      $userArray = [];
      foreach ($checkGroup->users as $user)
      {
          if ($user != Auth::user()->trainer_name)
          {
            $userArray[] = $user;
          }
      }
      $finalizeGroup = Group::where('id', $request->input('group-id'))->update(['status' => 'finalized']);
      if ($finalizeGroup)
      {
        $this->updateCache('groups');
        $userSearch = User::whereIn('trainer_name', $userArray)->get();

        $this->GroupFinalized($userSearch, $checkGroup);
        return array('Success' => 'True', 'Group' => $finalizeGroup);
      }
      else {
        return array('Success' => 'False', 'Error' => 'Unable to finalize group');
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to finalize group');
    }
  }

  public function Requeue_Group($request)
  {
    $userid = Auth::user()->trainer_name;
    $checkGroup = Group::where([['admin', $userid], ['id', $request->input('group-id')]])->first();
    if ($checkGroup)
    {
      $userArray = [];
      foreach ($checkGroup->users as $user)
      {
          if ($user != Auth::user()->trainer_name)
          {
            $userArray[] = $user;
          }
      }
      $userSearch = User::whereIn('trainer_name', $userArray)->get();

      foreach ($userSearch as $notify)
      {
        $notify->notify(new GroupRequeued($checkGroup));
        $notificationInfo = ['text' => Auth::user()->trainer_name . ' has requeued your group', 'url' => 'group/' . $checkGroup->id];
        event(new newNotification($notify->trainer_name, $notificationInfo));
      }

      $requeueGroup = Group::where('id', $request->input('group-id'))->update(['status' => 'queued']);
      $this->updateCache('groups');
      return array('Success' => 'True', 'Group' => $requeueGroup);
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to re-queue group');
    }
  }

  public function Transfer_Admin($request)
  {
    $userid = Auth::user()->trainer_name;
    $checkGroup = Group::where([['admin', $userid], ['id', $request->input('group-id')]])->first();
    if ($checkGroup)
    {
      // verify user exists in users array
      if (in_array($request->input('trainer'), $checkGroup->users))
      {
        $transferAdmin = Group::where('id', $request->input('group-id'))->update(['admin' => $request->input('trainer')]);

        if ($transferAdmin)
        {
          $this->updateCache('groups');
          $getUsers = Group::where('id', $request->input('group-id'))->first(['users', 'admin']);
          $userData = User::whereIn('trainer_name', $getUsers->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
          return array('Success' => 'True', 'Users' => $userData, 'Admin' => $getUsers->admin);
        }
        else {
          return array('Success' => 'False', 'Error' => 'Failed to transfer admin');
        }
      }
      else {
        return array('Success' => 'False', 'Error' => 'User doesnt belong to group');
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Group not found');
    }
  }

  public function Gather_Specific_Group($request, $groupID)
  {
    if (Auth::guest())
    {
      $userid = 'No';
    }
    else {
        $userid = Auth::user()->trainer_name;
    }
    $checkGroup = Group::where('id', $groupID)->first();
    if ($checkGroup)
    {
      $userData = User::whereIn('trainer_name', $checkGroup->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
      $groupChat = Group_Chat::where('group-id', $groupID)->get();
      if (in_array($userid, $checkGroup->users))
      {
        return array('inGroup' => true, 'Group' => $checkGroup, 'Chat' => $groupChat, 'User' => $userid, 'UserData' => $userData);
      }
      else {
        $checkInvites = Group_Invite::where([['invitee-id', $userid], ['group-id', $groupID]])
                    ->orwhere([['inviter-id', $userid], ['group-id', $groupID]])
                    ->first();
        if ($checkInvites)
        {
          if ($checkInvites['invitee-id'] == $checkInvites['inviter-id'])
          {
            return array('inGroup' => false, 'requested' => true, 'invited' => false, 'Group' => $checkGroup, 'Chat' => $groupChat, 'User' => $userid, 'UserData' => $userData);
          }
          else {
            return array('inGroup' => false, 'requested' => false, 'invited' => true, 'Group' => $checkGroup, 'Chat' => $groupChat, 'User' => $userid, 'UserData' => $userData);
          }
        }
        else {
          return array('inGroup' => false, 'requested' => false, 'invited' => false, 'Group' => $checkGroup, 'Chat' => $groupChat, 'User' => $userid, 'UserData' => $userData);
        }
      }
    }
    else {
      $maxID = Group::find(\DB::table('group')->max('id'));
      if ($maxID)
      {
        if ($maxID->id > $groupID)
        {
          return array('Success' => 'False', 'Expired' => 'True');
        }
      }
      return array('Success' => 'False', 'Error' => 'Group not found', 'Found' => 'False');
    }
  }

  public function Gather_Groups($request)
  {
    $userid = Auth::user()->id;
    $distance = $request->input('distance');
    $gatherProfileLoc = User::where('id', $userid)->first(['lat', 'lon']);
    $distanceArray = $this->gatherHighLow($gatherProfileLoc->lat, $gatherProfileLoc->lon, $distance);
    if (array_key_exists('error', $distanceArray))
    {
      return array('Success' => 'False', 'Error' => $distanceArray['error']);
    }
    else {
      if ($request->has('type'))
      {
        if ($request->has('subType1'))
        {
          if ($request->has('subType2'))
          {
            $gatherGroups = Group::where('status', 'queued')
                                   ->where('type', $request->input('type'))
                                   ->where('subType1', $request->input('subType1'))
                                   ->where('subType2', $request->input('subType2'))
                                   ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                   ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                   ->whereBetween('created_at', array(Carbon::now()->subDays(1), Carbon::now()))
                                   ->simplePaginate(10);
            //return $gatherGroups;
          }
          else {
            $gatherGroups = Group::where('status', 'queued')
                                   ->where('type', $request->input('type'))
                                   ->where('subType1', $request->input('subType1'))
                                   ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                   ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                   ->whereBetween('created_at', array(Carbon::now()->subDays(1), Carbon::now()))
                                   ->simplePaginate(10);
          }
          //return $gatherGroups;
        }
        else {
          $gatherGroups = Group::where('status', 'queued')
                                 ->where('type', $request->input('type'))
                                 ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                                 ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                                 ->whereBetween('created_at', array(Carbon::now()->subDays(1), Carbon::now()))
                                 ->simplePaginate(10);
        }
        //return $gatherGroups;
      }
      else {
        $gatherGroups = Group::where('status', 'queued')
                               ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                               ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                               ->whereBetween('created_at', array(Carbon::now()->subDays(1), Carbon::now()))
                               ->simplePaginate(10);
        //return $gatherGroups;
      }

      if ($gatherGroups)
      {
        return array('Success' => 'True', 'Group' => $gatherGroups);
      }
      else {
        return array('Success' => 'False', 'Error' => 'Unable to gather groups');
      }
      // modify get() to only get array of colunms relavent to user
    }
  }

  public function Gather_Users_Internal($lat, $lon, $distance, $userid)
  {
    $distanceArray = $this->gatherHighLow($lat, $lon, $distance);
    if (array_key_exists('error', $distanceArray))
    {
      return array('Success' => 'False', 'Error' => $distanceArray['error']);
    }
    else {
      $gatherUsers = User::where('id', '!=', $userid)->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])->get();
      return array('Success' => 'True', 'Group' => $gatherUsers);
    }
  }

  public function Gather_Users($request)
  {
    $userid = Auth::user()->id;
    $userLoc = User::where('id', $userid)->first(['lat', 'lon']);
    if ($request->has('distance'))
    {
      $distanceArray = $this->gatherHighLow($userLoc->lat, $userLoc->lon, $request->input('distance'));
      if (array_key_exists('error', $distanceArray))
      {
        return array('Success' => 'False', 'Error' => $distanceArray['error']);
      }
      else {
        $gatherUsers = User::where('id', '!=', $userid)->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])->get();
        return array('Success' => 'True', 'Group' => $gatherGroups);
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'No distance supplied');
    }
  }

  public function Gather_Home_Groups($distanceArray)
  {
    $getGroups = Group::where('status', 'queued')->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                        ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                        ->whereBetween('created_at', array(Carbon::now()->subDays(1), Carbon::now()))
                        ->take(10)
                        ->get();
    return $getGroups;
  }

  public function Redirect_User_Group($request)
  {
    $allGroups = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);
    $groupID = 'none';
    foreach ($allGroups as $group)
    {
      if (in_array(Auth::user()->trainer_name, $group->users) || Auth::user()->trainer_name == $group->admin)
      {
        $groupID = $group->id;
      }
    }
    if ($groupID != 'none')
    {
        return array('Success' => 'True', 'Group' => $groupID);
    }
    else {
      return array('Success' => 'False', 'Error' => 'Failed to redirect');
    }
  }
}

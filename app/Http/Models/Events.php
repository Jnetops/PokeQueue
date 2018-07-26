<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\User;
use App\Traits\Distance;
use App\Traits\GoogleApi;
use App\Traits\SendNotifications;
use App\Traits\CacheReturn;
use Carbon\Carbon;

class Events extends Model
{

  protected $fillable = ['admin', 'users', 'description', 'datetime', 'type', 'subType1', 'subType2', 'privacy', 'privacySubOption', 'location', 'address', 'lat', 'lon', 'timezone'];
  protected $table = 'events';
  use Distance;
  use GoogleApi;
  use SendNotifications;
  use CacheReturn;

  protected $casts = [
      'users' => 'array',
  ];


  public function CreateEvent($request)
  {

    $users = array(Auth::user()->trainer_name);
    $location = ucfirst($request->input('city')) . ', ' . $request->input('state');
    // get lat/long of addy
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
        $timezone = $timeZoneCheck['timezone'];
        $request->request->add(['timezone' => $timezone]);
        $dateInput = \Carbon\Carbon::parse($request->input('year') . "-" . $request->input('month') . "-" . $request->input('day') . ' ' . $request->input('time'))->setTimezone('UTC');
      }
    }

    $request->request->add(['users' => $users, 'location' => $location, 'admin' => Auth::user()->trainer_name, 'status' => 'queued', 'datetime' => $dateInput]);

    $eventCreate = Events::create($request->all());

    if ($eventCreate)
    {
      $friends = Friends::where([['request_uid', $eventCreate->admin], ['status', 'accepted']])->orWhere([['received_uid', $eventCreate->admin], ['status', 'accepted']])->get();

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

      $this->createEventNotification($users, $eventCreate);

      return array('Success' => 'True', 'Event' => $eventCreate);
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to create event');
    }

  }

  public function DeleteEvent($request)
  {
    $eventID = $request->input('event-id');
    $userid = Auth::user()->trainer_name;
    $eventsCheck = Events::where([['admin', $userid], ['id', $eventID]])->first();
    if ($eventsCheck)
    {
      $userArray = [];
      foreach ($eventsCheck->users as $user)
      {
          if ($user != Auth::user()->trainer_name)
          {
            $userArray[] = $user;
          }
      }
      $userSearch = User::whereIn('trainer_name', $userArray)->get();

      $this->disbandEvent($userSearch, $eventsCheck);

      $eventsDelete = $eventsCheck->delete();
      Events_Invite::where('event-id', $eventsCheck->id)->delete();
      Events_Chat::where('event-id', $eventsCheck->id)->delete();
      $this->updateCache('events');
      return array('Success' => 'True', 'Event' => $eventsDelete);
    }
    else
    {
      return array('Success' => 'False', 'Error' => 'Event doesnt exist or doesnt belong to user');
    }
  }

  public function Transfer_Admin($request)
  {
    $userid = Auth::user()->trainer_name;
    $checkEvent = Events::where([['admin', $userid], ['id', $request->input('event-id')]])->first();
    if ($checkEvent)
    {
      // verify user exists in users array
      if (in_array($request->input('trainer'), $checkEvent->users))
      {
        $transferAdmin = Events::where('id', $request->input('event-id'))->update(['admin' => $request->input('trainer')]);

        if ($transferAdmin)
        {
          $this->updateCache('events');
          $getUsers = Events::where('id', $request->input('event-id'))->first(['users', 'admin']);
          $userData = User::whereIn('trainer_name', $getUsers->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
          return array('Success' => 'True', 'Users' => $userData, 'Admin' => $getUsers->admin);
        }
        else {
          return array('Success' => 'False', 'Error' => 'Failed to transfer admin');
        }
      }
      else {
        return array('Success' => 'False', 'Error' => 'User doesnt belong to event');
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Event not found');
    }
  }

  public function ModifyEvent($request)
  {
    $userid = Auth::user()->trainer_name;
    $eventID = $request->input('id');
    //$request->request->add(['trainer_name' => $userid]);

    $eventsCheck = Events::where([['admin', $userid], ['id', $eventID]])->first();
    if ($eventsCheck)
    {
      $eventsUpdate = Events::where([['admin', $userid], ['id', $eventID]])->update($request->all());
      $this->updateCache('events');
      return array('Success' => 'True', 'Event' => $eventsUpdate);
    }
    else
    {
      return array('Success' => 'False', 'Error' => 'Event doesnt exist or doesnt belong to user');
    }
  }

  public function Leave_Event($request)
  {
    $checkEvent = Events::where('id', $request->input('event-id'))->first();
    if ($checkEvent)
    {
      if (Auth::user()->trainer_name != $checkEvent->admin)
      {
        if (in_array(Auth::user()->trainer_name, $checkEvent->users))
        {
          $users = $checkEvent->users;
          $k = array_search(Auth::user()->trainer_name, $users);
          unset($users[$k]);
          Events::find($request->input('event-id'))->update(['users' => $users]);
          $this->updateCache('events');
          $getUsers = Events::where('id', $request->input('event-id'))->first(['users', 'admin']);
          $userData = User::whereIn('trainer_name', $getUsers->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
          return array('Success' => 'True', 'Users' => $userData, 'Admin' => $getUsers->admin);
        }
        else {
          return array('Success' => 'False', 'Validation' => 'True', 'Error' => 'User Not In Event');
        }
      }
      else {
        return array('Success' => 'False', 'Validation' => 'True', 'Error' => 'Admin Cant Leave Event');
      }
    }
    else {
      return array('Success' => 'False', 'Validation' => 'True', 'Error' => 'No Event Found');
    }
  }

  public function GatherEvents($request)
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
            $gatherEvents = Events::where('type', $request->input('type'))
                            ->where('subType1', $request->input('subType1'))
                            ->where('subType2', $request->input('subType2'))
                            ->where('datetime', '>=', Carbon::today())
                            ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                            ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                            ->orderBy('datetime', 'asc')
                            ->simplePaginate(10);
            return array('Success' => 'True', 'Event' => $gatherEvents);
          }
          $gatherEvents = Events::where('type', $request->input('type'))
                          ->where('subType1', $request->input('subType1'))
                          ->where('datetime', '>=', Carbon::today())
                          ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                          ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                          ->orderBy('datetime', 'asc')
                          ->simplePaginate(10);
          return array('Success' => 'True', 'Event' => $gatherEvents);
        }
        $gatherEvents = Events::where('type', $request->input('type'))
                        ->where('datetime', '>=', Carbon::today())
                        ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                        ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                        ->orderBy('datetime', 'asc')
                        ->simplePaginate(10);
        return array('Success' => 'True', 'Event' => $gatherEvents);
      }
      else {
        $gatherEvents = Events::where('datetime', '>=', Carbon::today())
                        ->whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                        ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                        ->orderBy('datetime', 'asc')
                        ->simplePaginate(10);
        return array('Success' => 'True', 'Event' => $gatherEvents);
      }
      // modify get() to only get array of colunms relavent to user
    }
  }

  public function Gather_Home_Events($distanceArray)
  {
    $date = \Carbon\Carbon::now();

    $getEvents = Events::whereBetween('lat', [$distanceArray['latLow'], $distanceArray['latHigh']])
                ->whereBetween('lon', [$distanceArray['lonLow'], $distanceArray['lonHigh']])
                ->where('privacy', 0)->where('datetime', '>=', $date)->orderBy('datetime', 'asc')
                ->take(10)
                ->get();
    // $userArray = array();
    // foreach ($getEvents as $events)
    // {
    //   $userArray[] = $events->admin;
    // }
    // $getUsers = User::whereIn('trainer_name', $userArray)->get(['timezone']);
    return array('Success' => 'True', 'Event' => $getEvents);
  }

  public function GatherSpecificEvent($eventID)
  {
    $eventsGather = Events::where('id', $eventID)->first();
    if (Auth::guest())
    {
      $userid = 'No';
    }
    else {
        $userid = Auth::user()->trainer_name;
    }
    if ($eventsGather)
    {

      $userData = User::whereIn('trainer_name', $eventsGather->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
      $eventChat = Events_Chat::where('event-id', $eventID)->get();
      if (in_array($userid, $eventsGather->users))
      {
        $eventArray = array('inEvent' => true, 'event' => $eventsGather, 'Chat' => $eventChat, 'User' => $userid, 'UserData' => $userData);
      }
      else {
        $invite = Events_Invite::where([['invitee-id', $userid], ['event-id', $eventID]])
                    ->orwhere([['inviter-id', $userid], ['event-id', $eventID]])
                    ->first();
        if ($invite)
        {
          if ($invite['invitee-id'] == $invite['inviter-id'])
          {
            $eventArray = array('inEvent' => false, 'requested' => true, 'invited' => false, 'event' => $eventsGather, 'Chat' => $eventChat, 'User' => $userid, 'UserData' => $userData);
          }
          else {
            $eventArray = array('inEvent' => false, 'requested' => false, 'invited' => true, 'event' => $eventsGather, 'Chat' => $eventChat, 'User' => $userid, 'UserData' => $userData);
          }
        }
        else {
          $eventArray = array('inEvent' => false, 'requested' => false, 'invited' => false, 'event' => $eventsGather, 'Chat' => $eventChat, 'User' => $userid, 'UserData' => $userData);
        }
      }
      return array('Success' => 'True', 'Event' => $eventArray);
    }
    else {
      $maxID = Events::find(\DB::table('events')->max('id'));
      if ($maxID)
      {
        if ($maxID->id > $eventID)
        {
          return array('Success' => 'False', 'Expired' => 'True');
        }
      }
      return array('Success' => 'False', 'Error' => 'Event Doesnt Exist', 'Found' => 'False');
    }
  }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Events;
use App\Http\Models\Events_Invite;
use App\Http\Models\Events_Chat;
use App\Traits\TypeCheckFilters;
use App\Traits\ValidateInput;
use App\Traits\PrivacyCheck;
use View;
use File;
use Carbon\Carbon;
use Auth;
use App\Events\newMessage;
use App\Events\newMember;
use App\Events\transferAdmin;

class EventsController extends Controller
{
  use TypeCheckFilters;
  use ValidateInput;
  use PrivacyCheck;

  public function Event_Creation(Request $request)
  {
    // validate date and time supplied by user
    if ($request->has('privacy'))
    {
      $request->merge(['privacy' => '0']);
    }
    else {
      $request->request->add(['privacy' => '0']);
    }
    $checkTypes = $this->checkTypes($request);
    if (array_key_exists('error', $checkTypes) || array_key_exists('missing', $checkTypes))
    {
      if (array_key_exists('error', $checkTypes)) {
        return Redirect::back()->withErrors(['types', $checkTypes['error']]);
      }
      else {
        return Redirect::back()->withErrors(['types', $checkTypes['missing']]);
      }
    }
    else {
      $checkPrivacy = $this->ValidatePrivacy($request);
      if (array_key_exists('error', $checkPrivacy) || array_key_exists('missing', $checkPrivacy))
      {
        if (array_key_exists('error', $checkPrivacy)) {
          return redirect()->route('eventForm')->with('error', $checkPrivacy['error']);
        }
        else {
          return redirect()->route('eventForm')->with('error', $checkPrivacy['missing']);
        }
      }
      else {
        $validateResults = $this->eventCreateValidate($request);

        if($validateResults->passes()) {

          $events = new Events();
          $eventCreate = $events->CreateEvent($request);

          if ($eventCreate['Success'] == 'False')
          {
            return redirect()->route('eventForm')->with('error', $eventCreate['Error']);
          }
          else {
            return redirect()->route('specificEvent', [$eventCreate['Event']]);
          }

        }
        else {
          return redirect()->route('eventForm')->with('errors', $validateResults->errors());
        }
      }
    }
  }

  public function Event_Creation_Form(Request $request)
  {
    $path = public_path() . '/protos/pokemon.json';// ie: /var/www/laravel/app/storage/json/filename.json
    if (!File::exists($path)) {
        return 'failed';
    }

    $file = File::get($path); // string
    $file = json_decode($file, true);

    $pokemonArray = array();
    foreach ($file as $poke)
    {
      $pokemonArray[$poke['Name']] = (int)$poke['Number'];
    }

    return View::make('create_event',['pokemon' => $pokemonArray]);
  }

  public function Event_Deletion(Request $request)
  {
    $validateResults = $this->eventDeleteValidate($request);

    if($validateResults->passes()) {

      $events = new Events();
      $eventDelete = $events->DeleteEvent($request);
      if ($eventDelete['Success'] == 'False')
      {
        return $eventDelete;
      }
      else {
        return redirect()->action(
            'HomeController@index'
        );
      }
    } else {
      return $eventDelete;
    }
  }

  public function Event_Modify(Request $request)
  {
    // validate date and time supplied by user
    $checkTypes = $this->checkTypes($request);
    if (array_key_exists('error', $checkTypes))
    {
      return redirect()->action(
          'EventsController@Gather_Specific_Event', ['eventID' => $request->input('id'), 'Success' => 'False', 'Error' => 'Missing type values']
      );
    }
    else {
      $checkPrivacy = $this->ValidatePrivacy($request);
      if (array_key_exists('error', $checkPrivacy))
      {
        return redirect()->action(
            'EventsController@Gather_Specific_Event', ['eventID' => $request->input('id'), 'Success' => 'False', 'Error' => 'Missing type values']
        );
      }
      else {
        $validateResults = $this->eventModifyValidate($request);

        if($validateResults->passes()) {

          $events = new Events();

          $eventModify = $events->ModifyEvent($request);

          return redirect()->action(
              'EventsController@Gather_Specific_Event', ['eventID' => $request->input('id'), 'Success' => 'True', 'Error' => 'Missing type values']
          );
        } else {
          return redirect()->action(
              'EventsController@Gather_Specific_Event', ['eventID' => $request->input('id'), 'Success' => 'False', 'Error' => $validateResults->errors()]
          );
        }
      }
    }
  }

  public function leaveEvent(Request $request)
  {
    $validateInput = $this->leaveEventValidate($request);
    if ($validateInput->passes())
    {
      $event = new Events();
      $leaveEvent = $event->Leave_Event($request);
      if ($leaveEvent['Success'] == 'True')
      {
        $userArray = array('Users' => $leaveEvent['Users'], 'Admin' => $leaveEvent['Admin']);
        $public = array('type' => 'public', 'id' => $request->input('event-id'), 'subType' => 'event');
        $publicEvent = event(new newMember($public, $userArray));
        $private = array('type' => 'private', 'id' => $request->input('event-id'), 'subType' => 'event');
        $privateEvent = event(new newMember($private, $userArray));
      }
      else {
        return $leaveEvent;
      }
    }
    else {
      return array('Success' => 'False', 'Validation' => 'False', 'Errors' => $validateInput->errors());
    }
  }

  public function transferAdmin(Request $request)
  {
    $event = new Events();

    $validateResults = $this->transferAdminValidate($request);

    if ($validateResults->passes())
    {
      $transferOwnership = $event->Transfer_Admin($request);
      if ($transferOwnership['Success'] == 'True')
      {
        $userArray = array('Users' => $transferOwnership['Users'], 'Admin' => $transferOwnership['Admin']);
        $public = array('type' => 'public', 'id' => $request->input('event-id'), 'subType' => 'event');
        $publicEvent = event(new transferAdmin($public, $userArray));
        $private = array('type' => 'private', 'id' => $request->input('event-id'), 'subType' => 'event');
        $privateEvent = event(new transferAdmin($private, $userArray));
      }
      else {
        return $transferOwnership;
      }
    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function Gather_Events(Request $request)
  {
    $path = public_path() . '/protos/pokemon.json';// ie: /var/www/laravel/app/storage/json/filename.json
    if (!File::exists($path)) {
        return 'failed';
    }

    $file = File::get($path); // string
    $file = json_decode($file, true);

    $pokemonArray = array();
    foreach ($file as $poke)
    {
      $pokemonArray[$poke['Name']] = (int)$poke['Number'];

    }
    // validate distance supplied by user
    // validate month and year supplied by user
    $checkTypes = $this->checkTypes($request);
    if (array_key_exists('error', $checkTypes))
    {
      return back()->withErrors(['Success' => 'False', 'Error' => $checkTypes['error']]);
      return $checkTypes['error'];
    }
    else {
      $events = new Events();
      $gather = $events->GatherEvents($request);

      // gather filter cluster and pass to view
      $filterArray = array();
      if ($request->has('type')) {
        $filterArray['type'] = $request->input('type');
      }
      if ($request->has('subType1')) {
        $filterArray['subType1'] = $request->input('subType1');
      }
      if ($request->has('subType2')) {
        $filterArray['subType2'] = $request->input('subType2');
      }
      if ($request->has('distance')) {
        $filterArray['distance'] = $request->input('distance');
      }

      return View::make('all_events', ['events' => $gather, 'filters' => $filterArray, 'pokemon' => $pokemonArray]);
    }
  }

  public function Gather_Specific_Event(Request $request, $eventID)
  {
    // validate event id supplied
    $events = new Events();
    $gatherEvents = $events->GatherSpecificEvent($eventID);
    if (array_key_exists('Expired', $gatherEvents))
    {
      return View::make('expired', ['Type' => 'Event']);
    }
    if (array_key_exists('Found', $gatherEvents))
    {
      return View::make('notfound', ['Type' => 'Event']);
    }
    return View::make('specific_event', ['events' => $gatherEvents]);
  }

  public function Invite_To_Event(Request $request)
  {
    $validateResults = $this->eventTrainerValidate($request);

    if($validateResults->passes()) {

      $events = new Events_Invite();

      $eventInvite = $events->InviteToEvent($request);

      return $eventInvite;

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function Request_Invite_To_Event(Request $request)
  {
    $validateResults = $this->eventNonTrainerValidate($request);

    if($validateResults->passes()) {

      $events = new Events_Invite();

      $eventInviteRequest = $events->RequestInviteToEvent($request);

      return $eventInviteRequest;

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function Admin_Accept_Invite(Request $request)
  {
    $validateResults = $this->eventNonTrainerValidate($request);

    if($validateResults->passes()) {

      $events = new Events_Invite();
      $adminAccept = $events->AdminAcceptInvite($request);
      if ($adminAccept['Success'] == 'True')
      {
        $userArray = array('Users' => $adminAccept['Users'], 'Admin' => $adminAccept['Event']['admin']);
        $public = array('type' => 'public', 'id' => $request->input('event-id'), 'subType' => 'event');
        $publicEvent = event(new newMember($public, $userArray));
        $private = array('type' => 'private', 'id' => $request->input('event-id'), 'subType' => 'event');
        $privateEvent = event(new newMember($private, $userArray));
        return array('Success' => 'True');
      }
      else {
        return array('Success' => 'False');
      }

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function Admin_Reject_Invite(Request $request)
  {
    $validateResults = $this->eventNonTrainerValidate($request);

    if($validateResults->passes()) {

      $events = new Events_Invite();

      $adminReject = $events->AdminRejectInvite($request);

      return $adminReject;

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function User_Reject_Request(Request $request)
  {
    $validateResults = $this->eventTrainerValidate($request);

    if($validateResults->passes()) {

      $events = new Events_Invite();

      $userReject = $events->UserRejectRequest($request);

      return $userReject;

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function User_Accept_Request(Request $request)
  {
    $validateResults = $this->eventTrainerValidate($request);

    if($validateResults->passes()) {

      $events = new Events_Invite();

      $userAccept = $events->UserAcceptRequest($request);

      if ($userAccept['Success'] == 'True')
      {
        $userArray = array('Users' => $userAccept['Users'], 'Admin' => $userAccept['Event']['admin']);
        $public = array('type' => 'public', 'id' => $request->input('event-id'), 'subType' => 'event');
        $publicEvent = event(new newMember($public, $userArray));
        $private = array('type' => 'private', 'id' => $request->input('event-id'), 'subType' => 'event');
        $privateEvent = event(new newMember($private, $userArray));
        return array('Success' => 'True');
      }
      else {
        return array('Success' => 'False');
      }

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

  public function submitEventChat(Request $request)
  {
    $validateResults = $this->eventCommentValidate($request);

    if ($validateResults->passes())
    {
      $eventChat = new Events_Chat();
      $addChat = $eventChat->submit_event_chat($request);
      if ($addChat['Success'] == 'True')
      {
        $currDate = Carbon::now();
        $currDate = Carbon::parse($currDate)->setTimezone($addChat['Event']['timezone'])->format('g:i A T');
        if ($addChat['Chat']['trainer_team'] == 1)
        {
          $icon = '/images/mystic.png';
        }
        elseif ($addChat['Chat']['trainer_team'] == 2)
        {
          $icon = '/images/valor.png';
        }
        else {
          $icon = '/images/instinct.png';
        }
        $message = [
            'text' => e(ucfirst($request->input('comment'))),
            'trainer' => ucfirst(Auth::user()->trainer_name),
            'avatar' => $icon,
            'timestamp' => $currDate
        ];

        $typeInfo = array('type' => 'private', 'id' => $request->input('event-id'), 'subType' => 'event');

        $tests = event(new newMessage($message, $typeInfo));
      }
      else {
        return $addChat;
      }

    } else {
      return ['Success' => 'False', 'Error' => $validateResults->errors()];
    }
  }

}

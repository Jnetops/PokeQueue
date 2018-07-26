<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Models\Group_Queue;
use App\Http\Models\Group;
use App\Http\Models\Group_Invite;
use App\Http\Models\Group_Chat;
use Auth;
use View;
use File;
use Carbon\Carbon;
use App;
use App\Traits\TypeCheckFilters;
use App\Traits\ValidateInput;
use App\Events\newMessage;
use App\Events\transferAdmin;
use App\Events\newMember;
use App\Events\finalizeAll;
use App\Events\requeueAll;
use App\Events\disbandAll;


class GroupController extends Controller
{
    use TypeCheckFilters;
    use ValidateInput;
    // group model

    public function gatherPokes()
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

      return $pokemonArray;
    }

    public function createGroup(Request $request)
    {
      $checkTypes = $this->checkTypes($request);
      if (array_key_exists('error', $checkTypes))
      {
        return redirect()->route('groupForm')->with('error', $checkTypes['error']);
      }
      elseif (array_key_exists('missing', $checkTypes))
      {
        return redirect()->route('groupForm')->with('error', $checkTypes['missing']);
      }
      else {
        $validateInput = $this->groupCreateValidate($request);
        if ($validateInput->passes())
        {
          $group = new Group();
          $createGroup = $group->Create_Group($request);
          if ($createGroup['Success'] == 'False')
          {
            return redirect()->route('groupForm')->with('error', $createGroup['Error']);
          }
          else {
            return redirect()->route('specificGroup', [$createGroup['Group']]);
          }

        }
        else {
          return redirect()->route('groupForm')->with('errors', $validateInput->errors());
        }
      }
    }

    public function createRaidGroup(Request $request)
    {
      $group = new Group();
      $createRaidGroup = $group->Create_Raid_Group($request);
      if (array_key_exists('Exists', $createRaidGroup))
      {
        return array('Success' => 'False', 'Exists' => 'True', 'raidID' => $createRaidGroup['raidID'], 'groupID' => $createRaidGroup['groupID']);
      }
      elseif (array_key_exists('Error', $createRaidGroup))
      {
        return array('Success' => 'False', 'Exists' => 'False', 'Error' => $createRaidGroup['Error']);
      }
      else {
        return array('Success' => 'True', 'groupID' => $createRaidGroup['Group']->id);
      }
    }

    public function createGroupForm(Request $request)
    {
      $pokeArray = $this->gatherPokes();
      return View::make('create_group',['pokemon' => $pokeArray]);
    }

    public function disbandGroup(Request $request)
    {
      $group = new Group();
      $disbandGroup = $group->Disband_Group($request);
      if ($disbandGroup['Success'] == 'True')
      {
        $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
        $publicEvent = event(new disbandAll($public));
        $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
        $privateEvent = event(new disbandAll($private));
        return $disbandGroup;
      }
      else {
          return $disbandGroup;
      }
    }

    public function leaveGroup(Request $request)
    {
      $validateInput = $this->leaveGroupValidate($request);
      if ($validateInput->passes())
      {
        $group = new Group();
        $leaveGroup = $group->Leave_Group($request);
        if ($leaveGroup['Success'] == 'True')
        {
          $userArray = array('Users' => $leaveGroup['Users'], 'Admin' => $leaveGroup['Admin']);
          $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
          $publicEvent = event(new newMember($public, $userArray));
          $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
          $privateEvent = event(new newMember($private, $userArray));
        }
        else {
          return $leaveGroup;
        }
      }
      else {
        return array('Success' => 'False', 'Validation' => 'False', 'Errors' => $validateInput->errors());
      }
    }

    public function finalizeGroup(Request $request)
    {
      $group = new Group();
      $finalizeGroup = $group->Finalize_Group($request);
      if ($finalizeGroup['Success'] == 'True')
      {
        $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
        $publicEvent = event(new finalizeAll($public));
        $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
        $privateEvent = event(new finalizeAll($private));
        return $finalizeGroup;
      }
      else {
          return $finalizeGroup;
      }
    }

    public function requeueGroup(Request $request)
    {
      $group = new Group();
      $requeueGroup = $group->Requeue_Group($request);
      if ($requeueGroup['Success'] == 'True')
      {
        $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
        $publicEvent = event(new requeueAll($public));
        $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
        $privateEvent = event(new requeueAll($private));
        return $requeueGroup;
      }
      else {
          return $requeueGroup;
      }
    }

    public function transferAdmin(Request $request)
    {
      $group = new Group();

      $validateResults = $this->transferAdminValidate($request);

      if ($validateResults->passes())
      {
        $transferOwnership = $group->Transfer_Admin($request);
        if ($transferOwnership['Success'] == 'True')
        {
          $userArray = array('Users' => $transferOwnership['Users'], 'Admin' => $transferOwnership['Admin']);
          $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
          $publicEvent = event(new transferAdmin($public, $userArray));
          $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
          $privateEvent = event(new transferAdmin($private, $userArray));
        }
        else {
          return $transferAdmin;
        }
      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function gatherSpecificGroup(Request $request, $groupID)
    {
      $group = new Group();
      $gatherGroup = $group->Gather_Specific_Group($request, $groupID);
      if (array_key_exists('Expired', $gatherGroup))
      {
        return View::make('expired', ['Type' => 'Group']);
      }
      if (array_key_exists('Found', $gatherGroup))
      {
        return View::make('notfound', ['Type' => 'Group']);
      }
      return View::make('specific_group', ['groups' => $gatherGroup]);
    }

    public function gatherGroups(Request $request)
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

      $checkTypes = $this->checkTypes($request);
      if (array_key_exists('error', $checkTypes))
      {
        return $checkTypes['error'];
      }
      else {
        $group = new Group();
        $gatherGroup = $group->Gather_Groups($request);

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


        return View::make('all_groups', ['groups' => $gatherGroup, 'filters' => $filterArray, 'pokemon' => $pokemonArray]);
      }
    }

    public function gatherUsers(Request $request)
    {

      $group = new Group();
      $gatherUsers = $group->Gather_Users($request);
      echo $gatherUsers;
    }

    // group_invite model
    public function inviteToGroup(Request $request)
    {
      // requires group-id and user_id fields
      $validateResults = $this->groupTrainerValidate($request);

      if($validateResults->passes()) {

        $group = new Group_Invite();
        $inviteGroup = $group->Invite_To_Group($request);
        return $inviteGroup;

      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function inviteRequest(Request $request)
    {
      // requires group-id field
      $validateResults = $this->groupNonTrainerValidate($request);

      if($validateResults->passes()) {

        $group = new Group_Invite();
        $inviteGroup = $group->Invite_Request($request);
        return $inviteGroup;

      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function acceptRequest(Request $request)
    {
      // requires group-id
      $validateResults = $this->groupTrainerValidate($request);

      if($validateResults->passes()) {

        $group = new Group_Invite();
        $inviteGroup = $group->Accept_Requested_Invite($request);

        if ($inviteGroup['Success'] == 'True')
        {
          $userArray = array('Users' => $inviteGroup['Users'], 'Admin' => $inviteGroup['Group']['admin']);
          $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
          $publicEvent = event(new transferAdmin($public, $userArray));
          $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
          $privateEvent = event(new transferAdmin($private, $userArray));
          return array('Success' => 'True');
        }
        else {
          return $inviteGroup;
        }

      } else {
        return array('Success' => 'False', 'Error' => $validateResults->errors());
      }
    }

    public function acceptInvite(Request $request)
    {
      // requires group-id
      $validateResults = $this->groupNonTrainerValidate($request);

      if($validateResults->passes()) {

        $group = new Group_Invite();
        $inviteGroup = $group->Accept_Admin_Invite($request);

        if ($inviteGroup['Success'] == 'True')
        {
          $userArray = array('Users' => $inviteGroup['Users'], 'Admin' => $inviteGroup['Group']['admin']);
          $public = array('type' => 'public', 'id' => $request->input('group-id'), 'subType' => 'group');
          $publicEvent = event(new newMember($public, $userArray));
          $private = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');
          $privateEvent = event(new newMember($private, $userArray));
          return $inviteGroup;
        }
        else {
          return $inviteGroup;
        }

      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function rejectInvite($request)
    {
      $validateResults = $this->groupNonTrainerValidate($request);

      if($validateResults->passes()) {

        $group = new Group_Invite();
        $inviteGroup = $group->Reject_Admin_Invite($request);
        return $inviteGroup;

      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function rejectRequest($request)
    {
      $validateResults = $this->groupTrainerValidate($request);

      if($validateResults->passes()) {

        $group = new Group_Invite();
        $inviteGroup = $group->Reject_Requested_Invite($request);
        return $inviteGroup;

      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function submitGroupChat(Request $request)
    {
      $validateResults = $this->groupCommentValidate($request);

      if ($validateResults->passes())
      {
        $groupChat = new Group_Chat();
        $addChat = $groupChat->submit_group_chat($request);
        if ($addChat['Success'] == 'True')
        {
          $currDate = Carbon::now();
          $currDate = Carbon::parse($currDate)->setTimezone($addChat['Group']['timezone'])->format('g:i A T');
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

          $typeInfo = array('type' => 'private', 'id' => $request->input('group-id'), 'subType' => 'group');

          $tests = event(new newMessage($message, $typeInfo));
        }
        else {
          return $addChat;
        }

      } else {
        return ['Success' => 'False', 'Error' => $validateResults->errors()];
      }
    }

    public function redirectUserGroup(Request $request)
    {
      $group = new Group();
      $gatherGroup = $group->Redirect_User_Group($request);
      if ($gatherGroup == 'redirect failed')
      {
        return redirect()->action(
            'GroupController@createGroupForm'
        );
      }
      else {
        return redirect()->action(
            'GroupController@gatherSpecificGroup', ['groupID' => $gatherGroup]
        );
      }
    }

}

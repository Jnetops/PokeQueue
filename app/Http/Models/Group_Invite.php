<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;
use App\Traits\SendNotifications;
use App\Traits\CacheReturn;

class Group_Invite extends Model
{
  use SendNotifications;
  use CacheReturn;
  protected $fillable = ['inviter-id', 'invitee-id', 'group-id', 'status'];
  protected $table = 'group-invite';
  // serialize and unserialize JSON from TEXT field in DB
  // used to store userid's that are in a particular group
  protected $casts = [
      'users' => 'array',
  ];
  // group_invite model
  public function Invite_To_Group($request)
  {
    // user provides group id and user_id
    // make sure user sending request is in group
    // make sure user being invited isn't in group
    $userid = Auth::user()->trainer_name;
    if ($request->has('trainer'))
    {
      $inviteeid = $request->input('trainer');
    }
    else {
      return array('Success' => 'False', 'Error' => 'Please provide a user id');
    }
    if ($request->has('group-id'))
    {
      $groupid = $request->input('group-id');
    }
    else {
      return array('Success' => 'False', 'Error' => 'Please provide a group id');
    }
    $checkGroup = Group::where('admin', $userid)->first();
    $checkUser = User::where('trainer_name', $inviteeid)->first();
    $checkInvites = Group_Invite::where([['invitee-id', $inviteeid], ['group-id', $groupid]])
                    ->orwhere([['inviter-id', $inviteeid], ['group-id', $groupid]])
                    ->first();
    if ($checkGroup)
    {
      if (in_array($inviteeid, $checkGroup->users) || !$checkUser || $checkInvites)
      {
        // logic for adding user to group
        return array('Success' => 'False', 'Error' => 'Unable to invite user');
      }
      else {
        // logic for adding user to group
        $addInvite = Group_Invite::create([
          'inviter-id' => $userid,
          'invitee-id' => $inviteeid,
          'group-id' => $groupid,
          'status' => 'invited'
        ]);

        if ($addInvite)
        {
          $this->GroupInviteSend($checkUser, $checkGroup);
          return array('Success' => 'True', 'Group' => $addInvite);
        }
        else {
          return array('Success' => 'False', 'Error' => 'Failed to create invite');
        }
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to invite user');
    }

  }

  public function Invite_Request($request)
  {
    // user is outside group requesting invite
    // user supplies group id
    // query group id, verify it's valid
    // verify user isn't in group
    // query group-invites
    // verify user hadn't requested invite or been invited
    $userid = Auth::user()->trainer_name;
    if ($request->has('group-id'))
    {
      $groupid = $request->input('group-id');
    }
    else {
      return array('Success' => 'False', 'Error' => 'Please provide a group id');
    }
    $checkInvites = Group_Invite::where([['invitee-id', $userid], ['group-id', $groupid]])
                    ->orwhere([['inviter-id', $userid], ['group-id', $groupid]])
                    ->first();
    if ($checkInvites)
    {
      return array('Success' => 'False', 'Error' => 'Unable to request invite');
    }
    else {
      $checkGroup = Group::where('id', $groupid)->first();
      if (in_array($userid, $checkGroup->users))
      {
        return array('Success' => 'False', 'Error' => 'User already in group');
      }
      else {
        // if both inviter and invitee are same user id, user requested invite
        // if different, user was sent an invite
        $existingGroup = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);
        foreach ($existingGroup as $group)
        {
          if ($group->admin == $userid || in_array($userid, $group->users))
          {
            return array('Success' => 'False', 'Error' => 'User already in a group');
          }
        }

        $trainerCheck = User::where('trainer_name', $checkGroup->admin)->first();
        $addInvite = Group_Invite::create([
          'inviter-id' => $userid,
          'invitee-id' => $userid,
          'group-id' => $groupid,
          'status' => 'requested'
        ]);
        if ($addInvite)
        {
          $this->GroupInviteRequest($trainerCheck, $checkGroup);
          return array('Success' => 'True', 'Group' => $addInvite);
        }
        else {
          return array('Success' => 'False', 'Error' => 'Failed to create request');
        }
      }
    }
  }

  public function Accept_Admin_Invite($request)
  {
    // this fires when user accepts invite from admin of a group

    // make sure user isn't in Group
    // make sure user was invited to group
    // make sure req and rec id's arent same
    //
    $userid = Auth::user()->trainer_name;

    $groupid = $request->input('group-id');

    $getGroup = Group::where('id', $groupid)->first();
    if ($getGroup)
    {
      // check to make sure user isn't already in a group
      $inGroup = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);
      foreach ($inGroup as $group)
      {
        if (in_array($userid, $group->users))
        {
          return array('Success' => 'False', 'Error' => 'User already in group');
        }
      }

      if (count($getGroup->users) < $getGroup->count)
      {
        if (!in_array($userid, $getGroup->users))
        {
          $checkInvites = Group_Invite::where([['invitee-id', $userid], ['inviter-id', $getGroup->admin], ['group-id', $groupid], ['status', 'invited']])
                                        ->orWhere([['invitee-id', $getGroup->admin], ['inviter-id', $userid], ['group-id', $groupid], ['status', 'invited']])
                                        ->first();
          if ($checkInvites)
          {
            $groupUsers = $getGroup->users;
            array_push($groupUsers, $userid);
            $check = json_encode($groupUsers);
            $addToGroup = Group::where('id', $groupid)->update(['users' => $check]);
            $this->updateCache('groups');

            // create notification to all friends of user that they joined a group
            if ($addToGroup)
            {
              $regetGroup = Group::where('id', $groupid)->first();

              $friends = Friends::where([['request_uid', Auth::user()->trainer_name], ['status', 'accepted']])->orWhere([['received_uid', Auth::user()->trainer_name], ['status', 'accepted']])->get();

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

              $this->GroupInviteAccepted($users, $regetGroup, $userid);
            }

            $deleteInvite = Group_Invite::where([['invitee-id', $userid], ['inviter-id', $getGroup->admin], ['group-id', $groupid], ['status', 'invited']])
                                          ->orWhere([['invitee-id', $getGroup->admin], ['inviter-id', $userid], ['group-id', $groupid], ['status', 'invited']])
                                          ->delete();
            $userData = User::whereIn('trainer_name', $regetGroup->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
            return array('Success' => 'True', 'Group' => $regetGroup, 'Users' => $userData);
          }
          else {
            return array('Success' => 'False', 'Error' => 'No invite requested');
          }
        }
        else {
          return array('Success' => 'False', 'Error' => 'User already in group');
        }
      }
      else {
        return array('Success' => 'False', 'Error' => 'Group is full');
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to accept invite');
    }

  }

  public function Accept_Requested_Invite($request)
  {
    // this fires when admin accepts invites requested by other users

    // make sure group exists
    // if user that requested invite is already in another group, delete request
    // make sure person accepting is admin of group
    // make sure request was made
    $userid = Auth::user()->trainer_name;
    // implement validation for Parameters
    $groupid = $request->input('group-id');
    $trainer = $request->input('trainer');

    $getGroup = Group::where([['id', $groupid], ['admin', $userid]])->first();
    if ($getGroup)
    {
      $inGroup = $this->gather('groups', 'return \App\Http\Models\Group::get();', 30);
      foreach ($inGroup as $group)
      {
        if (in_array($trainer, $group->users))
        {
          return array('Success' => 'False', 'Error' => 'User already in group');
        }
      }

      if (count($getGroup->users) < $getGroup->count)
      {
        if (!in_array($trainer, $getGroup->users))
        {
          $checkInvites = Group_Invite::where([['invitee-id', $trainer], ['inviter-id', $trainer], ['group-id', $groupid], ['status', 'requested']])->first();
          if ($checkInvites)
          {
            $groupUsers = $getGroup->users;
            array_push($groupUsers, $trainer);
            $check = json_encode($groupUsers);
            $addToGroup = Group::where('id', $groupid)->update(['users' => $check]);
            $this->updateCache('groups');

            // create notification to all friends of user that they joined a group
            if ($addToGroup)
            {
              $regetGroup = Group::where('id', $groupid)->first();

              $friends = Friends::where([['request_uid', Auth::user()->trainer_name], ['status', 'accepted']])->orWhere([['received_uid', Auth::user()->trainer_name], ['status', 'accepted']])->get();

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

              $this->GroupInviteAccepted($users, $regetGroup, $trainer);
            }

            $deleteInvite = Group_Invite::where('invitee-id', $trainer)->where('inviter-id', $trainer)->where('status', 'requested')->where('group-id', $groupid)->delete();

            $userData = User::whereIn('trainer_name', $regetGroup->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
            return array('Success' => 'True', 'Group' => $regetGroup, 'Users' => $userData);
          }
          else {
            return array('Success' => 'False', 'Error' => 'No invite requested');
          }
        }
        else {
          return array('Success' => 'False', 'Error' => 'User already in group');
        }
      }
      else {
        return array('Success' => 'False', 'Error' => 'Group is full');
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Unable to accept invite');
    }

  }

  public function Reject_Requested_Invite($request)
  {
    $userid = Auth::user()->trainer_name;
    // implement validation for Parameters
    $groupid = $request->input('group-id');
    $trainer = $request->input('trainer');

    $getGroup = Group::where([['id', $groupid], ['admin', $userid]])->first();
    if ($getGroup)
    {
      $checkInvites = Group_Invite::where([['invitee-id', $trainer], ['inviter-id', $trainer], ['group-id', $groupid], ['status', 'requested']])->first();
      if ($checkInvites)
      {
        $deleteInvite = Group_Invite::where('invitee-id', $trainer)->where('inviter-id', $trainer)->where('status', 'requested')->where('group-id', $groupid)->delete();
        return array('Success' => 'True');
      }
      else {
        return array('Success' => 'False', 'Error' => 'No invite requested');
      }
    }
    else {
      return array('Success' => 'False', 'Error' => 'Not admin of group');
    }
  }

  public function Reject_Admin_Invite($request)
  {
    $userid = Auth::user()->trainer_name;

    $groupid = $request->input('group-id');

    $getGroup = Group::where('id', $groupid)->first();
    if ($getGroup)
    {

          $checkInvites = Group_Invite::where([['invitee-id', $userid], ['inviter-id', $getGroup->admin], ['group-id', $groupid], ['status', 'invited']])
                                        ->orWhere([['invitee-id', $getGroup->admin], ['inviter-id', $userid], ['group-id', $groupid], ['status', 'invited']])
                                        ->first();
          if ($checkInvites)
          {
            $deleteInvite = Group_Invite::where([['invitee-id', $userid], ['inviter-id', $getGroup->admin], ['group-id', $groupid], ['status', 'invited']])
                                          ->orWhere([['invitee-id', $getGroup->admin], ['inviter-id', $userid], ['group-id', $groupid], ['status', 'invited']])
                                          ->delete();
            return array('Success' => 'True');
          }
          else {
            return array('Success' => 'False', 'Error' => 'No invite requested');
          }
    }
    else {
      return array('Success' => 'False', 'Error' => 'No group found');
    }
  }
}

<?php

namespace App\Traits;

use App\User;
use Auth;
use App\Http\Models\Notifications;

use App\Notifications\AcceptedFriendRequest;
use App\Notifications\ReceivedFriendRequest;

use App\Notifications\CreateGroup;
use App\Notifications\GroupDisbanded;
use App\Notifications\GroupFinalized;
use App\Notifications\GroupInviteAccepted;
use App\Notifications\GroupInviteRequest;
use App\Notifications\GroupInviteSend;

use App\Notifications\CreateEvent;
use App\Notifications\EventDisbanded;
use App\Notifications\EventInviteAccepted;
use App\Notifications\EventInviteRequest;
use App\Notifications\EventInviteSend;
use App\Notifications\EventStartingSoon;

use App\Notifications\PokeTrackerAdd;
use App\Notifications\RaidTrackerAdd;


use App\Events\newNotification;

trait SendNotifications
{

  public function AcceptedFriendRequest($trainer, $requeryFriend)
  {
      $userNotify = User::where('trainer_name', $trainer)->first();
      $userNotify->notify(new AcceptedFriendRequest($requeryFriend));
  }

  public function ReceivedFriendRequest($trainer, $requeryFriend)
  {
    $userNotify = User::where('trainer_name', $trainer)->first();
    $userNotify->notify(new ReceivedFriendRequest($requeryFriend));
  }

  public function createGroupFunc($users, $groupCreate)
  {
    foreach ($users as $user)
    {
      $groupNotificationCreate = $user->notify(new CreateGroup($groupCreate));
      $notificationInfo = ['text' => Auth::user()->trainer_name . ' has created a new group for ' . $groupCreate->type, 'url' => 'group/' . $groupCreate->id];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

  public function createRaidGroup($users, $groupCreate)
  {
      foreach ($users as $user)
      {
        $groupNotificationCreate = $user->notify(new CreateGroup($groupCreate));
        $notificationInfo = ['text' => Auth::user()->trainer_name . ' has created a new raid group for ' . $groupCreate->type, 'url' => 'group/' . $groupCreate->id];
        event(new newNotification($user->trainer_name, $notificationInfo));
      }
  }

  public function GroupDisbanded($userSearch, $checkGroup)
  {
    foreach ($userSearch as $notify)
    {
      $notify->notify(new GroupDisbanded($checkGroup, 'User'));
      $notificationInfo = ['text' => Auth::user()->trainer_name . ' has disbanded your group', 'url' => 'group/' . $checkGroup->id];
      event(new newNotification($notify->trainer_name, $notificationInfo));
    }
    $typeArray = array(
      'App\Notifications\GroupInviteAccepted',
      'App\Notifications\GroupInviteRequest',
      'App\Notifications\GroupInviteSend',
      'App\Notifications\GroupInviteRequeued',
      'App\Notifications\GroupFinalized',
      'App\Notifications\CreateGroup'
    );

    $notifications = Notifications::whereIn('type', $typeArray)->get();
    foreach ($notifications as $notification)
    {
      $data = $notification->data;
      if ($data['group']['group_id'] == $checkGroup->id)
      {
        Notifications::where('id', $notification->id)->delete();
      }
    }
  }

  public function AutoGroupDisbanded($userSearch, $checkGroup)
  {
    foreach ($userSearch as $notify)
    {
      $notify->notify(new GroupDisbanded($checkGroup, 'Automatic'));
      $notificationInfo = ['text' => 'Group Has Been Automatically Disbanded Due To Time Limit', 'url' => 'group/' . $checkGroup->id];
      event(new newNotification($notify->trainer_name, $notificationInfo));
    }
    $typeArray = array(
      'App\Notifications\GroupInviteAccepted',
      'App\Notifications\GroupInviteRequest',
      'App\Notifications\GroupInviteSend',
      'App\Notifications\GroupInviteRequeued',
      'App\Notifications\GroupFinalized',
      'App\Notifications\CreateGroup'
    );

    $notifications = Notifications::whereIn('type', $typeArray)->get();
    foreach ($notifications as $notification)
    {
      $data = $notification->data;
      if ($data['group']['group_id'] == $checkGroup->id)
      {
        Notifications::where('id', $notification->id)->delete();
      }
    }
  }

  public function GroupFinalized($userSearch, $checkGroup)
  {
    foreach ($userSearch as $notify)
    {
      $notify->notify(new GroupFinalized($checkGroup));
      $notificationInfo = ['text' => Auth::user()->trainer_name . ' has finalized your group', 'url' => 'group/' . $checkGroup->id];
      event(new newNotification($notify->trainer_name, $notificationInfo));
    }
  }

  public function GroupInviteSend($checkUser, $checkGroup)
  {
    $checkUser->notify(new GroupInviteSend($checkGroup));
    $notificationInfo = ['text' => $checkGroup->admin . ' has invited you to a group', 'url' => 'group/' . $checkGroup->id];
    event(new newNotification($checkUser->trainer_name, $notificationInfo));
  }

  public function GroupInviteRequest($trainerCheck, $checkGroup)
  {
    $trainerCheck->notify(new GroupInviteRequest($checkGroup));
    $notificationInfo = ['text' => Auth::user()->trainer_name . ' has requested an invite to your group', 'url' => 'group/' . $checkGroup->id, 'user' => '/' . Auth::user()->trainer_name];
    event(new newNotification($trainerCheck->trainer_name, $notificationInfo));
  }

  public function GroupInviteAccepted($users, $regetGroup, $user)
  {
    foreach ($users as $user)
    {
      $groupInviteNotification = $user->notify(new GroupInviteAccepted($regetGroup));
      $notificationInfo = ['text' => $user . ' has joined a group hosted by ' . $regetGroup->admin, 'url' => 'group/' . $regetGroup->id];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

  public function createEventNotification($users, $eventCreate)
  {
    foreach ($users as $user)
    {
      $eventNotificationCreate = $user->notify(new CreateEvent($eventCreate));
      $notificationInfo = ['text' => $user->trainer_name . ' has created a new event', 'url' => '/events/' . $eventCreate->id];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

  public function disbandEvent($userSearch, $eventsCheck)
  {
    foreach ($userSearch as $notify)
    {
      $notify->notify(new EventDisbanded($eventsCheck));
      $notificationInfo = ['text' => $notify->trainer_name . ' has cancelled an event you where set to attend on ' . date('F d, Y', strtotime($eventsCheck->datetime))];
      event(new newNotification($notify->trainer_name, $notificationInfo));
    }

    $typeArray = array(
      'App\Notifications\EventInviteAccepted',
      'App\Notifications\EventInviteRequest',
      'App\Notifications\EventInviteSend',
      'App\Notifications\CreateEvent'
    );

    $notifications = Notifications::whereIn('type', $typeArray)->get();
    foreach ($notifications as $notification)
    {
      $data = $notification->data;
      if ($data['event']['event_id'] == $eventsCheck->id)
      {
        Notifications::where('id', $notification->id)->delete();
      }
    }
  }

  public function eventInviteAccept($users, $regetEvent, $user)
  {
    foreach ($users as $user)
    {
      $eventInviteNotification = $user->notify(new EventInviteAccepted($regetEvent));
      $notificationInfo = ['text' => $user . ' will be attending an event on ' . date('F d, Y', strtotime($regetEvent->datetime)), 'url' => 'events/' . $regetEvent->id];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

  public function eventInviteRequest($trainerCheck, $event)
  {
    $trainerCheck->notify(new EventInviteRequest($event));
    $notificationInfo = ['text' => Auth::user()->trainer_name . ' has requested an invite to an event your hosting on ' . date('F d, Y', strtotime($event->datetime)), 'url' => 'events/' . $event->id];
    event(new newNotification(Auth::user()->trainer_name, $notificationInfo));
  }


  public function eventInviteSend($trainerCheck, $event)
  {
    $trainerCheck->notify(new EventInviteSend($event));
    $notificationInfo = ['text' => $trainerCheck->trainer_name . ' has invited you to an event on ' . date('F d, Y', strtotime($event->datetime)), 'url' => 'events/' . $event->id];
    event(new newNotification($trainerCheck->trainer_name, $notificationInfo));
  }

  public function eventStartSoon($users, $event)
  {
    foreach ($users as $user)
    {
      $user->notify(new EventStartingSoon($event));
      $notificationInfo = ['text' => Auth::user()->trainer_name . ' Is Starting Soon: ' . date('F d, Y', strtotime($event->datetime)) . '!'];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

  public function pokeTrackerAdd($users, $regetPokemon)
  {
    foreach ($users as $user)
    {
      $pokeTrackerAddNotification = $user->notify(new PokeTrackerAdd($regetPokemon));
      $notificationInfo = ['text' => Auth::user()->trainer_name . ' has added a ' . $regetPokemon->pokemon_name . ' to the PokeTracker'];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

  public function raidTrackerAdd($users, $regetRaid)
  {
    foreach ($users as $user)
    {
      $pokeTrackerAddNotification = $user->notify(new RaidTrackerAdd($regetRaid));
      $notificationInfo = ['text' => Auth::user()->trainer_name . ' has added a ' . $regetRaid->star_level . ' star ' . $regetRaid->pokemon_name . ' raid to the RaidTracker'];
      event(new newNotification($user->trainer_name, $notificationInfo));
    }
  }

}

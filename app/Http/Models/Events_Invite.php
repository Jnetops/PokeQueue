<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Http\Models\Events;
use Auth;
use App\Traits\SendNotifications;
use App\Traits\CacheReturn;

class Events_Invite extends Model
{
		use SendNotifications;
		use CacheReturn;
		protected $table = 'events-invite';
		protected $fillable = ['invitee-id', 'inviter-id', 'status', 'event-id'];

		protected $casts = [
	      'users' => 'array',
	  ];

	  public function InviteToEvent($request)
	  {
			$eventid = $request->input('event-id');
			$trainer = $request->input('trainer');
			$userid = Auth::user()->trainer_name;

			$event = Events::where('id', $eventid)->where('admin', $userid)->first();

			$invite = Events_Invite::where([['invitee-id', $trainer], ['event-id', $eventid]])->orWhere([['inviter-id', $trainer], ['event-id', $eventid]])->first();

			if ($event && !$invite)
			{
			  // event exists and user submitting request is admin of it
			  if (!in_array($trainer, $event->users))
			  {
				// trainer that user is planning to invite is not currently a part of the event users
					$trainerCheck = User::where('trainer_name', $trainer)->first();
					if ($trainerCheck)
					{

					  $inviteArray = ['invitee-id' => $trainer, 'inviter-id' => $userid, 'event-id' => $eventid, 'status' => 'invited'];
					  $invite = Events_Invite::create($inviteArray);
						if ($invite)
						{
							$this->eventInviteSend($trainerCheck, $event);
							return array('Success' => 'True');
						}
						else {
							return array('Success' => 'False', 'Error' => 'Failed to create invite');
						}
					}
					else {
					  return array('Success' => 'False', 'Error' => 'Not a valid trainer');
					}

			  }
			  else {
				return array('Success' => 'False', 'Error' => 'User already attending event');
			  }
			}
			else {
			  return array('Success' => 'False', 'Error' => 'Unable to invite user to event');
			}
	  }

	  public function RequestInviteToEvent($request)
	  {
			$eventid = $request->input('event-id');
			$userid = Auth::user()->trainer_name;

			$event = Events::where('id', $eventid)->where('privacy', 0)->first();

			$invite = Events_Invite::where([['event-id', $eventid], ['invitee-id', $userid]])->orWhere([['event-id', $eventid], ['inviter-id', $userid]])->first();

			if ($event)
			{
				if (!$invite)
				{
					if (!in_array($userid, $event->users))
				  {
						$trainerCheck = User::where('trainer_name', $event->admin)->first();
						$inviteArray = ['invitee-id' => $userid, 'inviter-id' => $userid, 'event-id' => $eventid, 'status' => 'requested'];
						$invite = Events_Invite::create($inviteArray);
						if ($invite)
						{
							$this->eventInviteRequest($trainerCheck, $event);

							return array('Success' => 'True');
						}
						else {
							return array('Success' => 'False', 'Error' => 'Failed to create invite');
						}
				  }
				  else {
						return array('Success' => 'False', 'Error' => 'User already attending event');
				  }
				}
				else {
					return array('Success' => 'False', 'Error' => 'User already requested invite');
				}
			}
			else {
			  return array('Success' => 'False', 'Error' => 'Event doesnt exist');
			}

	  }

	  public function UserAcceptRequest($request)
	  {
			$eventid = $request->input('event-id');
			$userid = Auth::user()->trainer_name;
			$trainer = $request->input('trainer');

			$event = Events::where('admin', $userid)->where('id', $eventid)->first();

			// check if invite exists for the user and event
			$invite = Events_Invite::where([['invitee-id', '=', $trainer], ['inviter-id', '=', $trainer]])->where('status', 'requested')->where('event-id', $eventid)->first();
			// add query to if statement below to verify it returned results
			if ($event && $invite)
			{
			  if (!in_array($trainer, $event->users))
			  {
					$trainerCheck = User::where('trainer_name', $trainer)->first();
					if ($trainerCheck)
					{
					  // accept invite
					  $eventUsers = $event->users;
						array_push($eventUsers, $trainer);
						$check = json_encode($eventUsers);
						$addToEvent = Events::where('id', $eventid)->update(['users' => $check]);
						$this->updateCache('events');

						// create notification to all friends of user that they joined a group
						if ($addToEvent)
						{
						  $regetEvent = Events::where('id', $eventid)->first();

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
						  $this->eventInviteAccept($users, $regetEvent, $trainer);
						}

						$deleteInvite = Events_Invite::where([['invitee-id', $trainer], ['inviter-id', $trainer], ['event-id', $eventid], ['status', 'requested']])->delete();
						$userData = User::whereIn('trainer_name', $regetEvent->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
						return array('Success' => 'True', 'Event' => $regetEvent, 'Users' => $userData);

					}
					else {
					  return array('Success' => 'False', 'Error' => 'User doesnt exist');
					}
			  }
			  else {
				return array('Success' => 'False', 'Error' => 'User already attending event');
			  }
			}
			else {
			  return array('Success' => 'False', 'Error' => 'Unable to invite to event');
			}
	  }

	  public function UserRejectRequest($request)
	  {
			$eventid = $request->input('event-id');
			$userid = Auth::user()->trainer_name;
			$trainer = $request->input('trainer');

			$event = Events::where('admin', $userid)->where('id', $eventid)->first();

			// check if invite exists for the user and event
			$invite = Events_Invite::where([['invitee-id', $trainer], ['inviter-id', $trainer]])->where('status', 'requested')->where('event-id', $eventid)->first();
			// add query to if statement below to verify it returned results

			if ($event && $invite)
			{
			  if (in_array($userid, $event->users))
			  {
				$trainerCheck = User::where('trainer_name', $trainer)->first();
				if ($trainerCheck)
				{

				  $delete = Events_Invite::where('id', $invite->id)->delete();
					if ($delete)
					{
						return array('Success' => 'True');
					}
					else {
						return array('Success' => 'False', 'Error' => 'Failed to delete invite');
					}
				}
				else {
				  return array('Success' => 'False', 'Error' => 'User doesnt exist');
				}
			  }
			  else {
				return array('Success' => 'False', 'Error' => 'User already attending event');
			  }
			}
			else {
			  return array('Success' => 'False', 'Error' => 'Unable to invite to event');
			}
	  }

	  public function AdminAcceptInvite($request)
	  {
			$eventid = $request->input('event-id');
			$userid = Auth::user()->trainer_name;

			$event = Events::where('id', $eventid)->where('admin', '!=', $userid)->first();
			// check if invite exists, add check in if check below to make sure both exist
			$invite = Events_Invite::where('invitee-id', $userid)->orWhere('inviter-id', $userid)->where('status', 'invited')->where('event-id', $eventid)->first();

			if ($event && $invite)
			{
			  if (!in_array($userid, $event->users))
			  {
					//accept invite
					$eventUsers = $event->users;
		      array_push($eventUsers, $userid);
		      $check = json_encode($eventUsers);
		      $addToEvent = Events::where('id', $eventid)->update(['users' => $check]);
					$this->updateCache('events');

		      // create notification to all friends of user that they joined a group
		      if ($addToEvent)
		      {
		        $regetEvent = Events::where('id', $eventid)->first();

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

		        $this->eventInviteAccept($users, $regetEvent, $userid);
		      }

		      $deleteInvite = Events_Invite::where([['invitee-id', $userid], ['inviter-id', $event->admin], ['event-id', $eventid], ['status', 'invited']])
		                                    ->orWhere([['invitee-id', $event->admin], ['inviter-id', $userid], ['event-id', $eventid], ['status', 'invited']])
		                                    ->delete();
			  $userData = User::whereIn('trainer_name', $regetEvent->users)->get(['trainer_team', 'trainer_level', 'trainer_name']);
		      return array('Success' => 'True', 'Event' => $regetEvent, 'Users' => $userData);

			  }
			  else {
				return array('Success' => 'False', 'Error' => 'Already attending event');
			  }
			}
			else {
			  return array('Success' => 'False', 'Error' => 'Unable to join event');
			}
	  }

	  public function AdminRejectInvite($request)
	  {
			$eventid = $request->input('event-id');
			$userid = Auth::user()->trainer_name;

			$event = Events::where('id', $eventid)->where('admin', '!=', $userid)->first();
			// check if invite exists, add check in if check below to make sure both exist
			$invite = Events_Invite::where('invitee-id', $userid)->orWhere('inviter-id', $userid)->where('status', 'invited')->where('event-id', $eventid)->first();

			if ($event && $invite)
			{
			  if (!in_array($userid, $event->users))
			  {
				//reject invite
					$delete = Events_Invite::where('id', $invite->id)->delete();
					if ($delete)
					{
						return array('Success' => 'True');
					}
					else {
						return array('Success' => 'True', 'Error' => 'Failed to delete invite');
					}
			  }
			  else {
			  	return array('Success' => 'False', 'Error' => 'Already accepted invite');
			  }
			}
			else {
			  return array('Success' => 'False', 'Error' => 'Not invited to event');
			}
	  }

}

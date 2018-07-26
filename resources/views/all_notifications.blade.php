@extends('layouts.app')


<link href="{{ asset('css/notifications_all.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
  $("#mark-all").click(function() {
    $.get( "/notification/all/markallread" + this.value, function( data ) {
	  	if (data == 'true')
		{
			window.location.href = "/dashboard";
		}
		else {
			alert("Unable to clear all notifications");
		}
	});
  });
});
</script>

@section('content')
<div class="row">
  <div class="col-md-12 center-block text-center">
    <div class="notification-container center-block text-center">
      <div class="panel panel-default">
        <div class="panel panel-body">
          @if ($notifications->isEmpty())
            <li class="notification-li">
              <h5 class="notify-h5">You Have No Notifications At This Time</h5>
            </li>
          @else
          <ul class="notification-ul">
            @foreach ($notifications as $notification)
              @if ($notification->type == 'App\Notifications\CreateEvent')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['event_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>{{ $decodedArray['date'] }}</b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="event pull-right btn btn-default" id="{{ $decodedArray['event_id'] }}">View Event</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\CreateGroup')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['group_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>
                          @foreach ($decodedArray['types'] as $type)
                            {{$type}}
                          @endforeach
                          </b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="group pull-right btn btn-default" id="{{ $decodedArray['group_id'] }}">View Group</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\EventDisbanded')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}}</b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\EventInviteAccepted')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['event_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}}</b>{{$decodedArray['statement2']}}<b>{{$decodedArray['host']}}</b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="event pull-right btn btn-default" id="{{ $decodedArray['event_id'] }}">View Event</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\EventInviteRequest')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['event_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}}</b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="btn btn-default" id="accept-event-invite" data-user="{{Auth::user()->trainer_name}}" data-id="{{$decodedArray['event_id']}}">Accept Request</button>
                        <button class="event pull-right btn btn-default" id="{{ $decodedArray['event_id'] }}">View Event</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\EventInviteSend')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['event_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}}</b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="event pull-right btn btn-default" id="{{ $decodedArray['event_id'] }}">View Event</button>
                        <button class="btn btn-default" id="accept-event-invite" data-id="{{$decodedArray['event_id']}}">Accept Invite</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\GroupDisbanded')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\GroupFinalized')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['group_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="group pull-right btn btn-default" id="{{ $decodedArray['group_id'] }}">View Group</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\GroupInviteSend')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['group_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>
                          @foreach ($decodedArray['types'] as $type)
                          {{$type}}
                          @endforeach
                        </b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="group pull-right btn btn-default" id="{{ $decodedArray['group_id'] }}">View Group</button>
                        <button class="btn btn-default" id="accept-group-invite" data-id="{{$decodedArray['group_id']}}">Accept Invite</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\GroupInviteRequest')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['group_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}</h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="group pull-right btn btn-default" id="{{ $decodedArray['group_id'] }}">View Group</button>
                        <button class="btn btn-default" id="accept-group-invite" data-user="{{ $decodedArray['user'] }}" data-id="{{$decodedArray['group_id']}}">Accept Request</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\GroupInviteAccepted')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['group_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}{{$decodedArray['statement2']}}<b>{{$decodedArray['host']}}</b></h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="group pull-right btn btn-default" id="{{ $decodedArray['group_id'] }}">View Group</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\PokeTrackerAdd')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['tracker_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}<b>{{$decodedArray['pokemon']}}</b>{{$decodedArray['statement2']}}</h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="poketracker pull-right btn btn-default" id="{{ $decodedArray['tracker_id'] }}">View Pokemon</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\AcceptedFriendRequest')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['user'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}</h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="friend pull-right btn btn-default" id="{{ $decodedArray['user'] }}">View User</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\ReceivedFriendRequest')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['user'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}</h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="friend pull-right btn btn-default" id="{{ $decodedArray['user'] }}">View User</button>
                        <button class="btn btn-default" id="accept-friend-request" data-user="{{Auth::user()->trainer_name}}">Accept Request</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @elseif ($notification->type == 'App\Notifications\RaidTrackerAdd')
                @foreach ($notification->data as $decodedArray)
                  <li class="notification-li" id="{{ $decodedArray['tracker_id'] }}">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="notify-h5"><b>{{ $decodedArray['user'] }}</b>{{ $decodedArray['statement'] }}</h5>
                      </div>
                      <div class="col-md-4">
                        <button class="mark-read pull-right btn btn-default" id="{{$notification->id}}">Mark As Read</button>
                        <button class="raidtracker pull-right btn btn-default" id="{{ $decodedArray['tracker_id'] }}">View Raid</button>
                      </div>
                    </div>
                  </li>
                @endforeach
              @endif
            @endforeach
          @endif
          </ul>
        </div>
        <div class="panel panel-footer">
          <button class="btn btn-default" id="mark-all">Mark All As Read</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

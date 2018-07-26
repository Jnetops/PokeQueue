@extends('layouts.app')

<link href="{{ asset('css/profile.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/profile.js') }}"></script>

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">

      <div class="panel panel-default">

        <div class="panel panel-body" id="profile-panel-body">
          <div class="row">
            <div class="col-md-12">
              @if ($profile['profile']->trainer_team == 1)
                <div class="col-md-12 team-image top" style="background-image: url('/images/mystic.png'); background-color: 	#000;">
              @elseif ($profile['profile']->trainer_team == 2)
                <div class="col-md-12 team-image top" style="background-image: url('/images/valor.png'); background-color: 	#000;">
              @else
                <div class="col-md-12 team-image top" style="background-image: url('/images/instinct.png'); background-color: 	#000;">
              @endif
                <h4 id="trainer-level-display">Level {{ $profile['profile']->trainer_level }}</h4>
                <h4 id="trainer-name-display">{{ ucfirst($profile['profile']->trainer_name) }}</h4>
              </div>
              @if ($profile['isFriends'] == false)
                @if ($profile['hasRequested'] == true)
                  <button class="btn btn-default add-friend" disabled>Requested</button>
                @elseif ($profile['profile']->trainer_name == Auth::user()->trainer_name)

                @else
                  <button class="btn btn-default add-friend">Add Friend</button>
                @endif
              @else
                <button class="btn btn-default add-friend" disabled>Friends</button>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
  		<div class="panel panel-default group-panel">
  			<div class="panel panel-heading group-heading">
  			  <h4 class="text-center">Group</h4>
  			</div>

  			<div class="panel panel-body group-body">
          <ul class="group-ul">
            @if ($profile['group'] == '')
              <li class="group-li inactive-group">
                <div class="text-center">
                  <h3 class="text-center">{{ ucfirst($profile['profile']->trainer_name) }} Is Not In A Group</h3>
                </div>
              </li>
            @else
              <li class="group-li">
                <div class="panel group">
                  <div class="panel-heading group">
                    @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($profile['group']['created_at']))->format('%H') == '00')
                      <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($profile['group']['created_at']))->format('%i min') }}</h3>
                    @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($profile['group']['created_at']))->format('%H') == '01')
                      <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($profile['group']['created_at']))->format('%h hour %i min') }}</h3>
                    @else
                      <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($profile['group']['created_at']))->format('%h hours %i min') }}</h3>
                    @endif
                  </div>
                  <div class="panel-body group">
                    <div class="text-center group-image-div">

                      @if ($profile['group']['type'] == 1)
                        @if ($profile['group']['subType1'] == 1)
                          <img src="{{url('/images/mystic-battles.jpg')}}" alt="Image" class="group-image"/>
                        @elseif ($profile['group']['subType1'] == 2)
                          <img src="{{url('/images/valor-battles.jpg')}}" alt="Image" class="group-image"/>
                        @elseif ($profile['group']['subType1'] == 3)
                          <img src="{{url('/images/instinct-battles.jpg')}}" alt="Image" class="group-image"/>
                        @endif
                      @elseif ($profile['group']['type'] == 2)
                        @if ($profile['group']['subType1'] == 1)
                          <img src="{{url('/images/event-farming.jpg')}}" alt="Image" class="group-image"/>
                        @elseif ($profile['group']['subType1'] == 2)
                          <div class="col-md-12 poke-catch" style="background-image: url('/images/poke-catch.jpg');">
                            <img src="{{ URL::to('/') }}/images/sprites/{{$profile['group']['subType2']}}.png" alt="Image" class="group-image-pokemon"/>
                            <div class="text-center">
                              <h1><b>Nest Farming</b></h1>
                            </div>
                          </div>
                        @elseif ($profile['group']['subType1'] == 3)
                          <img src="{{url('/images/farming-all.jpg')}}" alt="Image" class="group-image"/>
                        @endif
                      @elseif ($profile['group']['type'] == 3)
                        <img src="{{url('/images/item-farming.jpg')}}" alt="Image" class="group-image"/>
                      @elseif ($profile['group']['type'] == 4)
                        <img src="{{ URL::to('/') }}/images/sprites/{{$profile['group']['subType2']}}.png" alt="Image" class="group-raid-image"/>
                      @endif
                    </div>
                    <div class="col-md-6 height2">
                      <h4 class="group-location text-center">Address</h4>
                      <h4 class="group-address text-center">{{ $profile['group']['address'] }}</h4>
                      <h4 class="group-location text-center">{{ $profile['group']['location'] }}</h4>
                    </div>
                    <div class="col-md-6">
                      <h4 class="group-time text-center">Leader: <a href="/users/{{ ucfirst($profile['group']['admin']) }}">{{ ucfirst($profile['group']['admin']) }}</a></h4>
                    </div>
                    <div class="col-md-12 height2">
                      <h5 class="text-center"><b>Description:</b></h5>
                      @if ($profile['group']['type'] == 4)
                        <h3 align="center" class="group-description"><b>{{ $profile['group']['description'] }}</b></h3>
                      @else
                        <p align="center" class="group-description">{{ $profile['group']['description'] }}</p>
                      @endif
                    </div>
                    <div class="col-md-12">
                      <button class="btn btn-default view-group" data-id="{{$profile['group']['id']}}">View Group</button>
                    </div>
                  </div>
                </div>
              </li>
            @endif
          </ul>
  			</div>
		</div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-default events-panel">
  			<div class="panel panel-heading events-heading">
  			  <h4 class="text-center">Attending Events</h4>
  			</div>
  			<div class="panel panel-body events-body">
          <ul class="events-ul">
            @if (count($profile['events']) <= 0)
              <li class="event-li inactive-event">
                <div class="text-center">
                  <h3 class="text-center">{{ ucfirst($profile['profile']->trainer_name) }} Isnt Attending Any Events!</h3>
                </div>
              </li>
            @else
              @foreach ($profile['events'] as $key => $eventArray)
                <li class="event-li">
                  <div class="panel event">
                      <div id="event-{{ $eventArray['id'] }}">
                        @if (!$loop->first)
                          @if ($profile['events'][$key - 1]['datetime'] != $eventArray['datetime'])
                            <div class="panel-heading event">
                              <h3 class="event-date text-center">{{ \Carbon\Carbon::parse($profile['events'][$key]['datetime'])->format('F j, Y') }}</h3>
                            </div>
                          @endif
                        @else
                          <div class="panel-heading event">
                            <h3 class="event-date text-center">{{ \Carbon\Carbon::parse($eventArray['datetime'])->format('F j, Y') }}</h3>
                          </div>
                        @endif
                        <div class="panel-body event">
                          <div class="text-center">
                            @if ($eventArray['type'] == 1)
                              @if ($eventArray['subType1'] == 1)
                                <img src="{{url('/images/mystic-battles.jpg')}}" alt="Image" class="event-image"/>
                              @elseif ($eventArray['subType1'] == 2)
                                <img src="{{url('/images/valor-battles.jpg')}}" alt="Image" class="event-image"/>
                              @elseif ($eventArray['subType1'] == 3)
                                <img src="{{url('/images/instinct-battles.jpg')}}" alt="Image" class="event-image"/>
                              @endif
                            @elseif ($eventArray['type'] == 2)
                              @if ($eventArray['subType1'] == 1)
                                <img src="{{url('/images/event-farming.jpg')}}" alt="Image" class="event-image"/>
                              @elseif ($eventArray['subType1'] == 2)
                                <div class="col-md-12 poke-catch" style="background-image: url('/images/poke-catch.jpg');">
                                  <img src="{{ URL::to('/') }}/images/sprites/{{$eventArray['subType2']}}.png" alt="Image" class="event-image-pokemon"/>
                                  <div class="text-center">
                                    <h1><b>Nest Farming</b></h1>
                                  </div>
                                </div>
                              @elseif ($eventArray['subType1'] == 3)
                                <img src="{{url('/images/farming-all.jpg')}}" alt="Image" class="event-image"/>
                              @endif
                            @elseif ($eventArray['type'] == 3)
                              <img src="{{url('/images/item-farming.jpg')}}" alt="Image" class="event-image"/>
                            @endif
                          </div>
                          <div class="col-md-6 height2">
                            <h4 class="text-center">Address</h4>
                            <h4 class="event-address text-center">{{ $eventArray['address'] }}</h4>
                            <h4 class="event-location text-center">{{ $eventArray['location'] }}</h4>
                          </div>
                          <div class="col-md-6">
                            <h4 class="event-time text-center">Start Time: {{ \Carbon\Carbon::parse($eventArray['datetime'])->format('g:i A') }}</h4>
                            <h4 class="event-time text-center">Leader: <a href="/users/{{ ucfirst($eventArray['admin']) }}">{{ ucfirst($eventArray['admin']) }}</a></h4>
                          </div>
                          <div class="col-md-12 height2">
                            <h5 class="text-center"><b>Description:</b></h5>
                            <p align="center" class="event-description">{{ $eventArray['description'] }}</p>
                          </div>
                          <div class="col-md-12">
                            <button class="btn btn-default view-event" data-id="{{$eventArray['id']}}">View Event</button>
                          </div>
                        </div>
                      </div>
                  </div>
              </li>
              @endforeach
            @endif
          </ul>
  			</div>
		   </div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-default friends-panel">
        <div class="panel panel-heading friends-heading">
          <h4 class="text-center">Friends ({{count($profile['friends'])}})</h4>
        </div>

        <div class="panel panel-body friends-body-user">
          <ul class="friends-ul-user">
            @if (count($profile['friends']) <= 0)
              <li class="friend-li inactive-friends">
                <div class="text-center">
                  <h3 class="text-center">You have no friends yet!</h3>
                </div>
              </li>
            @else
              @foreach ($profile['friends'] as $friend)
                <li class="friends-li">
                  <a href="/users/{{$friend->trainer_name}}">
                    @if ($friend->trainer_team == 1)
                      <div class="col-md-12 team-image" style="background-image: url('/images/mystic.png'); background-color: 	#000;">
                    @elseif ($friend->trainer_team == 2)
                      <div class="col-md-12 team-image" style="background-image: url('/images/valor.png'); background-color: 	#000;">
                    @else
                      <div class="col-md-12 team-image" style="background-image: url('/images/instinct.png'); background-color: 	#000;">
                    @endif
                      <h5 id="trainer-level-display">Level {{ $friend->trainer_level }}</h5>
                      <h4 id="trainer-name-display">{{ucfirst($friend->trainer_name)}}</h4>
                    </div>
                  </a>
                </li>
              @endforeach
            @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

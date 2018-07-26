@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link href="{{ asset('css/home.css') }}" rel="stylesheet">

<script>
$(document).ready(function() {

  $("#create-raid").click( function() {
    window.location.href = "/raid/tracker/add"
  });

  $("#create-pokemon").click( function() {
    window.location.href = "/poke/tracker/add"
  });

  $("#create-event").click( function() {
    window.location.href = "/events/create"
  });

  $("#create-group").click( function() {
    window.location.href = "/group/create"
  });

  $("#view-all-pokemon").click( function() {
    window.location.href = "/poke/tracker?distance=25"
  });

  $("#view-all-raids").click( function() {
    window.location.href = "/raid/tracker?distance=25"
  });

   $(".view-group").click(function() {
     window.location.href = "/group/" + $(this).attr("data-id");
   });

   $(".view-event").click(function() {
     window.location.href = "/events/" + $(this).attr("data-id");
   });

   $(".create-raid-group").click(function() {

     $.ajax({url: "/group/raid/create", type: "POST", data: {"_token": "{{ csrf_token() }}", 'raid-id':$(this).attr('data-id')}, success: function(result){

       if (result['Success'] == 'False')
       {
           if (result['Exists'] == 'False')
           {
             $(".alert-warning").remove();
             $(".home-container").prepend('<div class="row alert alert-warning text-center"><h4 class="error-message">'+result["Error"]+'</h4></div>');
             setTimeout(function() {
                $('.alert-warning').remove();
              }, 10000);
           }
           else {
             $("#view-group").attr("data-id", result['groupID']);
             $("#group-exists-modal").modal('toggle');
           }
       }
       else {
         window.location.href = "/group/" + result['groupID'];
       }
     }});

   });

   $("#ignore-current").click(function() {
     $.ajax({url: "/group/raid/create", type: "POST", data: {"_token": "{{ csrf_token() }}", 'raid-id':$(".create-raid-group").attr('data-id'), 'exists':'ignore'}, success: function(result){

       if (result['Response'] == 'Failed')
       {
           if (result['Exists'] == 'false')
           {
             alert(result["Error"]);
           }
           else {
             $("#view-group").attr("data-id", result['groupID']);
             $("#group-exists-modal").modal('toggle');
           }
       }
       else {
         window.location.href = "/group/" + result['groupID'];
       }
     }});
   });

   $("#view-group").click(function() {
     window.location.href = "group/" + $("#view-group").attr("data-id");
   });

});

</script>

@section('content')
<div class="container home-container">
    @if(session()->has('error'))
      <div class="row alert alert-warning text-center">
          <h4 class="error-message">{{session('error')}}</h4>
      </div>
    @endif
    <div class="row">
        <div class="col-md-12">

            <div class="col-md-6 poke-tracker">
              <div class="text-center tracker-title">
                Local Pokemon
                <a class="all"href="/poke/tracker?distance=25"> (All Pokemon)</a>
              </div>
              @if (!$pokemon->isEmpty())
                <ul class="pokemon-ul">
                @foreach ($pokemon as $pokemon)
                <li class="pokemon-li">
                  <div class="panel pokemon">
                    <div class="panel-heading pokemon">
                      <h4 class="text-center">{{$pokemon->pokemon_name}} ({{ $pokemon->pokemon_rarity }})</h4>
                      @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($pokemon->pokemon_expire))->format('%H') == '00')
                        <h5 class="text-center" id="expire"><b>Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($pokemon->pokemon_expire))->format('%i min') }}</b></h5>
                      @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($pokemon->pokemon_expire))->format('%H') == '01')
                        <h5 class="text-center" id="expire"><b>Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($pokemon->pokemon_expire))->format('%h hour %i min') }}</b></h5>
                      @else
                        <h5 class="text-center" id="expire"><b>Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($pokemon->pokemon_expire))->format('%h hours %i min') }}</b></h5>
                      @endif
                    </div>
                    <div class="panel-body pokemon" id="panel-{{$pokemon->id}}">
                      <div class="row" id="{{$pokemon->id}}">
                        <div class="col-md-3 text-center" id="name-rarity">
                          <img src="{{ URL::to('/') }}/images/sprites/{{$pokemon->pokemon_id}}.png" alt="Image" id="poke-tracker-image"/>
                        </div>
                        <div class="col-md-3 text-center" id="cp-iv">
                          @if (!empty($pokemon->pokemon_cp))
                            <h5 class="text-center"><b>CP: {{$pokemon->pokemon_cp}}</b></h5>
                          @else
                            <h5 class="text-center"><b>CP: </b><b class="not-known">Not Known</b></h5>
                          @endif
                          @if (!empty($pokemon->pokemon_iv))
                            <h5 class="text-center"><b>IV: {{$pokemon->pokemon_iv}}</b></h5>
                          @else
                            <h5 class="text-center"><b>IV: </b><b class="not-known">Not Known</b></h5>
                          @endif
                        </div>
                        <div class="col-md-3 text-center" id="moves">
                          @if (!empty($pokemon->pokemon_move_1))
                            <h5 class="text-center"><b>Fast: {{$pokemon->pokemon_move_1}}</b></h5>
                          @else
                            <h5 class="text-center"><b>Fast: </b><b class="not-known">Not Known</b></h5>
                          @endif
                          @if (!empty($pokemon->pokemon_move_2))
                            <h5 class="text-center"><b>Charge: {{$pokemon->pokemon_move_2}}</b></h5>
                          @else
                            <h5 class="text-center"><b>Charge: </b><b class="not-known">Not Known</b></h5>
                          @endif
                        </div>
                        <div class="col-md-3 text-center" id="expire-loc">
                          <h5 class="text-center"><b>Latitude: {{$pokemon->pokemon_lat}}</b></h5>
                          <h5 class="text-center"><b>Longitude: {{$pokemon->pokemon_lon}}</b></h5>
                        </div>
                        <div class="col-md-12">
                          <a class="btn btn-default" id="specific-pokemon" href="/poke/tracker/{{$pokemon->id}}">View/Share Pokemon</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                @endforeach
                </ul>
              @else
                <div class="text-center inactive">
                  <h3 class="text-center">No active pokemon in your area</h3>
                  <button id="create-pokemon"><h5><b>Submit Pokemon Spotting</b></h5></button>
                </div>
              @endif
            </div>

            <div class="col-md-6 raid-tracker">
              <div class="text-center tracker-title">
                Local Raids
                <a class="all"href="/raid/tracker?distance=25"> (All Raids)</a>
              </div>
              @if (!$raids->isEmpty())
                <ul class="raid-ul">
                @foreach ($raids as $raid)
                  <li class="raid-li">
                    <div class="panel raid">
                      <div class="panel-heading raid">
                        <h4 class="text-center">{{$raid->pokemon_name}} - {{$raid->star_level}} Star</h4>
                        @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%H') == '00')
                          <h5 class="text-center"><b>Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%i min') }}</b></h5>
                        @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%H') == '01')
                          <h5 class="text-center"><b>Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%h hour %i min') }}</b></h5>
                        @else
                          <h5 class="text-center"><b>Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%h hours %i min') }}</b></h5>
                        @endif
                      </div>
                      <div class="panel-body raid" id="panel-{{$raid->id}}">
                        <div class="row">
                          <div class="col-md-6 text-center">
                            <img src="{{ URL::to('/') }}/images/sprites/{{$raid->pokemon_id}}.png" alt="Image" id="raid-tracker-image"/>
                          </div>
                          <div class="col-md-6 text-center">
                            <h4 class="text-center">{{$raid->address}}</h4>
                            <h4 class="text-center">{{$raid->location}}</h4>
                            <h5 class="text-center"><b>Latitude: {{$raid->gym_lat}}</b></h5>
                            <h5 class="text-center"><b>Longitude: {{$raid->gym_lon}}</b></h5>
                          </div>
                        </div>
                      </div>
                      <div class="panel-footer">
                        <a class="btn btn-default create-raid-group" id="specific-raid" href="/raid/tracker/{{$raid->id}}">Group For/Share Raid</a>
                      </div>
                    </div>
                  </li>
                @endforeach
                </ul>
              @else
                <div class="text-center inactive">
                  <h3 class="text-center">No active raids in your area</h3>
                  <button class="text-center" id="create-raid"><h5><b>Create New Raid</b></h5></button>
                </div>
              @endif
            </div>

            <div class="col-md-6 event">
              <div class="text-center tracker-title">
                Upcoming Events <a class="all"href="/events?distance=25">(View All)</a>
              </div>
              @if (!$events['Event']->isEmpty())
                <ul class="event-ul">
                    @foreach ($events['Event'] as $key => $eventArray)
                      <li class="event-li">
                        <div class="panel event">
                            <div id="event-{{ $eventArray['id'] }}">
                              @if (!$loop->first)
                                @if ($events['Event'][$key - 1]['datetime'] != $eventArray['datetime'])
                                  <div class="panel-heading event">
                                    <h3 class="event-date text-center">{{ \Carbon\Carbon::parse($eventArray['datetime'])->format('F j, Y') }}</h3>
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
                                <div class="col-md-6 height">
                                  <h4 class="text-center">Address</h4>
                                  <h4 class="event-address text-center">{{ $eventArray['address'] }}</h4>
                                  <h4 class="event-location text-center">{{ $eventArray['location'] }}</h4>
                                </div>
                                <div class="col-md-6">
                                  <h4 class="event-time text-center">Start Time: {{ \Carbon\Carbon::parse($eventArray['datetime'])->format('g:i A') }} {{\Carbon\Carbon::now()->setTimezone($eventArray['timezone'])->format('T')}}</h4>
                                  <h4 class="event-time text-center">Leader: <a href="/users/{{ ucfirst($eventArray['admin']) }}">{{ ucfirst($eventArray['admin']) }}</a></h4>
                                </div>
                                <div class="col-md-12 height">
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
                </ul>
              @else
                <div class="text-center inactive">
                  <h3 class="text-center">No active Events in your area</h3>
                  <button class="text-center" id="create-event"><h5><b>Create Event</b></h5></button>
                </div>
              @endif
            </div>
            <div class="col-md-6 group">
              <div class="text-center tracker-title">
                Active Groups <a class="all" href="/groups?distance=25">(View All)</a>
              </div>
              @if (!$groups->isEmpty())
                <ul class="group-ul">
                  @foreach ($groups as $key => $group)
                    <li class="group-li">
                      <div class="panel group">
                        <div class="panel-heading group">
                          @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%H') == '00')
                            <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%i min') }}</h3>
                          @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%H') == '01')
                            <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%h hour %i min') }}</h3>
                          @else
                            <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%h hours %i min') }}</h3>
                          @endif
                        </div>
                        <div class="panel-body group">
                          <div class="text-center">

                          @if ($group['type'] == 1)
                            @if ($group['subType1'] == 1)
                              <img src="{{url('/images/mystic-battles.jpg')}}" alt="Image" class="group-image"/>
                            @elseif ($group['subType1'] == 2)
                              <img src="{{url('/images/valor-battles.jpg')}}" alt="Image" class="group-image"/>
                            @elseif ($group['subType1'] == 3)
                              <img src="{{url('/images/instinct-battles.jpg')}}" alt="Image" class="group-image"/>
                            @endif
                          @elseif ($group['type'] == 2)
                            @if ($group['subType1'] == 1)
                              <img src="{{url('/images/event-farming.jpg')}}" alt="Image" class="group-image"/>
                            @elseif ($group['subType1'] == 2)
                              <div class="col-md-12 poke-catch" style="background-image: url('/images/poke-catch.jpg');">
                                <img src="{{ URL::to('/') }}/images/sprites/{{$group['subType2']}}.png" alt="Image" class="group-image-pokemon"/>
                                <div class="text-center">
                                  <h1><b>Nest Farming</b></h1>
                                </div>
                              </div>
                            @elseif ($group['subType1'] == 3)
                              <img src="{{url('/images/farming-all.jpg')}}" alt="Image" class="group-image"/>
                            @endif
                          @elseif ($group['type'] == 3)
                            <img src="{{url('/images/item-farming.jpg')}}" alt="Image" class="group-image"/>
                          @elseif ($group['type'] == 4)
                            <img src="{{ URL::to('/') }}/images/sprites/{{$group['subType2']}}.png" alt="Image" class="raid-group-image"/>
                          @endif
                          </div>
                          <div class="col-md-6 height">
                            <h4 class="group-location text-center">Address</h4>
                            <h4 class="group-address text-center">{{ $group['address'] }}</h4>
                            <h4 class="group-location text-center">{{ $group['location'] }}</h4>
                          </div>
                          <div class="col-md-6">
                            <h4 class="group-time text-center">Leader: <a href="/users/{{ ucfirst($group['admin']) }}">{{ ucfirst($group['admin']) }}</a></h4>
                          </div>
                          <div class="col-md-12 height">
                            <h5 class="text-center"><b>Description:</b></h5>
                            @if ($group['type'] == 4)
                              <h3 align="center" class="group-description"><b>{{ $group['description'] }}</b></h3>
                            @else
                              <p align="center" class="group-description">{{ $group['description'] }}</p>
                            @endif
                          </div>
                          <div class="col-md-12">
                            <button class="btn btn-default view-group" data-id="{{$group['id']}}">View Group</button>
                          </div>
                        </div>
                      </div>
                    </li>
                  @endforeach
                </ul>
              @else
                <div class="text-center inactive">
                  <h3 class="text-center">No queued groups in your area</h3>
                  <button class="text-center" id="create-group"><h5><b>Queue Group</b></h5></button>
                </div>
              @endif
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="group-exists-modal" tabindex="-1" role="dialog" aria-labelledby="group-exists" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="group-exists-title">A Group Exists For This Raid: <h5 class="text-center" id="transfer-error"></h5></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5> A group exists for this raid, would you like to create a new group?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="ignore-current" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-primary" data-id="" id="view-group">View Group</button>
      </div>
    </div>
  </div>
</div>
@endsection

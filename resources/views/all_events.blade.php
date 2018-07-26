@extends('layouts.app')

<link href="{{ asset('css/events.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/event.js') }}"></script>

@section('content')
<input type="hidden" value="all-events" id="page-type">
<div class="container event-container">
    <div class="row">
        <div class="col-md-12 event-col-12">
          <h1 class="event-title text-center">Event Finder</h1>
          <div class="col-md-12 button-col event-col-12">
            <a class="btn btn-primary btn-lg create-event-button" href="/events/create">Create Event</a>
          </div>
          <div class="col-md-12 text-center">
            <select id="distance">
            </select>
          </div>
          <div class="col-md-12 filter-col">
            <h3 class="text-center">Filter Options</h3>
            <div class="col-md-3 filter-col-3">
              @if (array_key_exists('type', $filters))
                @if ($filters['type'] == 1)
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Gym Battles / Farming)</option>
                    <option value="1" selected="selected">Gym Battles</option>
                    <option value="2">Pokemon Farming</option>
                    <option value="3">Item Farming</option>
                  </select>
                @elseif ($filters['type'] == 2)
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Gym Battles / Farming)</option>
                    <option value="1">Gym Battles</option>
                    <option value="2" selected="selected">Pokemon Farming</option>
                    <option value="3">Item Farming</option>
                  </select>
                @else
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Gym Battles / Farming)</option>
                    <option value="1">Gym Battles</option>
                    <option value="2">Pokemon Farming</option>
                    <option value="3" selected="selected">Item Farming</option>
                  </select>
                @endif
              @else
                <select class="form-control type" id="type" name="type">
                  <option selected="selected">Select Type (Gym Battles / Farming)</option>
                  <option value="1">Gym Battles</option>
                  <option value="2">Pokemon Farming</option>
                  <option value="3">Item Farming</option>
                </select>
              @endif


            </div>
            <div class="col-md-3 filter-col-3">
              @if (array_key_exists('type', $filters))
                @if ($filters['type'] == 2)
                  @if (array_key_exists('subType1', $filters))
                    @if ($filters['subType1'] == 1)
                      <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                        <option>Select What To Farm</option>
                        <option value="1" selected="selected">Event Farming</option>
                        <option value="2">Nest Farming</option>
                        <option value="3">All Farming</option>
                      </select>
                    @elseif ($filters['subType1'] == 2)
                      <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                        <option>Select What To Farm</option>
                        <option value="1">Event Farming</option>
                        <option value="2" selected="selected">Nest Farming</option>
                        <option value="3">All Farming</option>
                      </select>
                    @elseif ($filters['subType1'] == 3)
                      <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                        <option>Select What To Farm</option>
                        <option value="1">Event Farming</option>
                        <option value="2">Nest Farming</option>
                        <option value="3" selected="selected">All Farming</option>
                      </select>
                    @endif
                  @else
                    <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                      <option selected="selected">Select What To Farm</option>
                      <option value="1">Event Farming</option>
                      <option value="2">Nest Farming</option>
                      <option value="3">All Farming</option>
                    </select>
                  @endif
                  <select class="form-control teams" id="teams" name="subType1">
                    <option selected="selected">Select Team</option>
                    <option value="1">Mystic</option>
                    <option value="2">Valor</option>
                    <option value="3">Instinct</option>
                  </select>
                @elseif ($filters['type'] == 1)
                  @if (array_key_exists('subType1', $filters))
                    @if ($filters['subType1'] == 1)
                      <select class="form-control teams" id="teams" name="subType1">
                        <option>Select Team</option>
                        <option value="1" selected="selected">Mystic</option>
                        <option value="2">Valor</option>
                        <option value="3">Instinct</option>
                      </select>
                    @elseif ($filters['subType1'] == 2)
                      <select class="form-control teams" id="teams" name="subType1">
                        <option>Select Team</option>
                        <option value="1">Mystic</option>
                        <option value="2" selected="selected">Valor</option>
                        <option value="3">Instinct</option>
                      </select>
                    @else
                      <select class="form-control teams" id="teams" name="subType1">
                        <option>Select Team</option>
                        <option value="1">Mystic</option>
                        <option value="2">Valor</option>
                        <option value="3" selected="selected">Instinct</option>
                      </select>
                    @endif
                  @else
                    <select class="form-control teams" id="teams" name="subType1">
                      <option selected="selected">Select Team</option>
                      <option value="1">Mystic</option>
                      <option value="2">Valor</option>
                      <option value="3">Instinct</option>
                    </select>
                  @endif
                  <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                    <option selected="selected">Select What To Farm</option>
                    <option value="1">Event Farming</option>
                    <option value="2">Nest Farming</option>
                    <option value="3">All Farming</option>
                  </select>
                @endif
              @else
                <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                  <option selected="selected">Select What To Farm</option>
                  <option value="1">Event Farming</option>
                  <option value="2">Nest Farming</option>
                  <option value="3">All Farming</option>
                </select>

                <select class="form-control teams" id="teams" name="subType1">
                  <option selected="selected">Select Team</option>
                  <option value="1">Mystic</option>
                  <option value="2">Valor</option>
                  <option value="3">Instinct</option>
                </select>
              @endif

            </div>
            <div class="col-md-3 filter-col-3">
              @if (array_key_exists('type', $filters))
                @if ($filters['type'] == 2)
                  @if (array_key_exists('subType1', $filters))
                    @if ($filters['subType1'] == 2)
                      <select class="form-control nest-farming" id="nest-farming" name="subType2">
                        @foreach ($pokemon as $key => $value)
                          @if ($value == $filters['subType2'])
                            <option value="{{$value}}" selected="selected">{{$key}}</option>
                          @else
                            <option value="{{$value}}">{{$key}}</option>
                          @endif
                        @endforeach
                      </select>
                    @endif
                  @else
                    <select class="form-control nest-farming" id="nest-farming" name="subType2">
                      <option selected="selected">Select Pokemon Nest</option>
                      @foreach ($pokemon as $key => $value)
                        <option value="{{$value}}">{{$key}}</option>
                      @endforeach
                    </select>
                  @endif
                @else
                  <select class="form-control nest-farming" id="nest-farming" name="subType2">
                    <option selected="selected">Select Pokemon Nest</option>
                    @foreach ($pokemon as $key => $value)
                      <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                  </select>
                @endif
              @else
                <select class="form-control nest-farming" id="nest-farming" name="subType2">
                  <option selected="selected">Select Pokemon Nest</option>
                  @foreach ($pokemon as $key => $value)
                    <option value="{{$value}}">{{$key}}</option>
                  @endforeach
                </select>
              @endif
            </div>
            <div class="col-md-3">
              <button class="btn btn-default" id="filter">Filter</button>
            </div>
          </div>
          <div class="col-md-12 event-sub-col-12">
            <div class="panel panel-default event-panel">
                <div class="panel-body event-body">
                    @if (!count($events['Event']))
                      <h1 class="no-event text-center">No events Found</h1>
                    @else
                      @foreach ($events['Event'] as $key => $event)
                        @if (!$loop->first && \Carbon\Carbon::parse($events['Event'][$key - 1]['datetime'])->format('F j, Y') != \Carbon\Carbon::parse($events['Event'][$key]['datetime'])->format('F j, Y'))
                          <div class="date">
                            <h3 class="text-center date-h3">{{ \Carbon\Carbon::parse($events['Event'][$key]['datetime'])->format('F j, Y') }}</h3>
                          </div>
                        @elseif ($loop->first)
                          <div class="date">
                            <h3 class="text-center date-h3">{{ \Carbon\Carbon::parse($events['Event'][$key]['datetime'])->format('F j, Y') }}</h3>
                          </div>
                        @endif

                          <div class="row event-row">
                            <div class="col-md-12">
                              <h3 class="event-time text-center">Start Time: {{ \Carbon\Carbon::parse($event['datetime'])->format('g:i A') }}  {{\Carbon\Carbon::now()->setTimezone($event['timezone'])->format('T')}}</h3>
                              <div class="col-md-6">
                                @if ($event['type'] == 1)
                    							@if ($event['subType1'] == 1)
                    								<img src="{{url('/images/mystic-battles.jpg')}}" alt="Image" class="event-image"/>
                    							@elseif ($event['subType1'] == 2)
                    								<img src="{{url('/images/valor-battles.jpg')}}" alt="Image" class="event-image"/>
                    							@elseif ($event['subType1'] == 3)
                    								<img src="{{url('/images/instinct-battles.jpg')}}" alt="Image" class="event-image"/>
                    							@endif
                    						@elseif ($event['type'] == 2)
                    							@if ($event['subType1'] == 1)
                    								<img src="{{url('/images/event-farming.jpg')}}" alt="Image" class="event-image"/>
                    							@elseif ($event['subType1'] == 2)
                                    <div class="col-md-12 poke-catch" style="background-image: url('/images/poke-catch.jpg');">
                    								  <img src="{{ URL::to('/') }}/images/sprites/{{$event['subType2']}}.png" alt="Image" class="event-image pokemon"/>
                                      <div class="text-center">
                                        <h1><b>Nest Farming</b></h1>
                                      </div>
                                    </div>
                    							@elseif ($event['subType1'] == 3)
                    								<img src="{{url('/images/farming-all.jpg')}}" alt="Image" class="event-image"/>
                    							@endif
                    						@elseif ($event['type'] == 3)
                    							<img src="{{url('/images/item-farming.jpg')}}" alt="Image" class="event-image"/>
                    						@endif
                              </div>
                              <div class="Col-md-6">
                                <h3 class="text-center">Description</h3>
                                <h4 class="text-long">{{ $event['description'] }}</h4>
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="col-md-4">
                                <h3 class="text-center">Location</h3>
                                <h4 class="text-center text-long">{{ $event['address'] }}<h4>
                                <h4 class="text-center text-long">{{ $event['location'] }}</h4>
                              </div>
                              <div class="Col-md-4">
                                <h3 class="text-center">Admin</h3>
                                <h4 class="text-center">
                                  <a href="/users/{{ $event['admin'] }}">
                                    <b>{{ ucfirst($event['admin']) }}</b>
                                  </a>
                                </h4>
                              </div>
                              <div class="col-md-4">
                                <div class="col-md-12">
                                  <h3 class="text-center">Trainers Attending:</h3>
                                  <h4 align="center" class="event-members text-center">
                                    @if (count($event['users']) == 1)
                                      <h4 class="text-center">No Trainers Attending</h4>
                                    @else
                                      @foreach ($event['users'] as $users)

                                        @if ($users != $event['admin'])
                                          &nbsp;<a href="/users/{{ $users }}"><b>{{ ucfirst($users) }}</b></a>
                                        @endif
                                      @endforeach
                                    @endif
                                  </h4>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="text-center">
                                <a class="btn btn-default view-event" href="/events/{{ $event['id'] }}">View Event</a>
                              </div>
                            </div>
                          </div>
                      @endforeach
                    @endif
                </div>

            </div>
          </div>
        </div>
    </div>
    @if (count($events['Event']))
    <div class="row controls">
      <div class="col-md-12 text-center">
        @if ($events['Event']->previousPageUrl() != null)
        <div class="col-md-6 nav-col">
          <button class="btn btn-default prev" id="nav-button" data-url="{{$events['Event']->previousPageUrl()}}">Prev</button>
        </div>
        @else
        <div class="col-md-6 nav-col">
          <button disabled="disabled" class="btn btn-default prev" id="nav-button" data-url="{{$events['Event']->previousPageUrl()}}">Prev</button>
        </div>
        @endif
        @if ($events['Event']->nextPageUrl() != null)
        <div class="col-md-6 nav-col">
          <button class="btn btn-default next" id="nav-button" data-url="{{$events['Event']->nextPageUrl()}}">Next</button>
        </div>
        @else
        <div class="col-md-6 nav-col">
          <button disabled="disabled" class="btn btn-default next" id="nav-button" data-url="{{$events['Event']->nextPageUrl()}}">Next</button>
        </div>
        @endif
      </div>
  </div>
  @endif
</div>
@endsection

@extends('layouts.app')

<link href="{{ asset('css/groups.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/group.js') }}"></script>


@section('content')
<input type="hidden" value="all-groups" id="page-type">
<div class="container group-container">
    <div class="row">
        <div class="col-md-12 group-col-12">
          <h1 class="group-title text-center">Group Finder</h1>
          <div class="col-md-12 button-col">
            <a class="btn btn-primary btn-lg create-group-button" href="/group/create">Create Group</a>
          </div>
          <div class="col-md-12 text-center">
            <select id="distance"></select>
          </div>
          <div class="col-md-12 filter-col">
            <h3 class="text-center">Filter Options</h3>
            <div class="col-md-3 filter-col-3">
              @if (array_key_exists('type', $filters))
                @if ($filters['type'] == 1)
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Raid / Battle / Farming)</option>
                    <option value="1" selected="selected">Gym Battles</option>
                    <option value="2">Pokemon Farming</option>
                    <option value="3">Item Farming</option>
                    <option value="4">Raids</option>
                  </select>
                @elseif ($filters['type'] == 2)
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Raid / Battle / Farming)</option>
                    <option value="1">Gym Battles</option>
                    <option value="2" selected="selected">Pokemon Farming</option>
                    <option value="3">Item Farming</option>
                    <option value="4">Raids</option>
                  </select>
                @elseif ($filters['type'] == 3)
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Raid / Battle / Farming)</option>
                    <option value="1">Gym Battles</option>
                    <option value="2">Pokemon Farming</option>
                    <option value="3" selected="selected">Item Farming</option>
                    <option value="4">Raids</option>
                  </select>
                @elseif ($filters['type'] == 4)
                  <select class="form-control type" id="type" name="type">
                    <option>Select Type (Raid / Battle / Farming)</option>
                    <option value="1">Gym Battles</option>
                    <option value="2">Pokemon Farming</option>
                    <option value="3">Item Farming</option>
                    <option value="4" selected="selected">Raids</option>
                  </select>
                @endif
              @else
                <select class="form-control type" id="type" name="type">
                  <option selected="selected">Select Type (Raid / Battle / Farming)</option>
                  <option value="1">Gym Battles</option>
                  <option value="2">Pokemon Farming</option>
                  <option value="3">Item Farming</option>
                  <option value="4">Raids</option>
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
          <div class="col-md-12 group-sub-col-12">
            <div class="panel panel-default">
                <div class="panel-body group-body">
                    @if (!count($groups['Group']))
                      <h1 class="no-group text-center">No Groups Found</h1>
                    @else
                      @foreach ($groups['Group'] as $key => $group)
                        <div class="row group-row">
                          <div class="col-md-12 queue-time">
                            @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%H') == '00')
                              <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%i min') }}</h3>
                            @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%H') == '01')
                              <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%h hour %i min') }}</h3>
                            @else
                              <h3 class="group-time text-center">Queue Time: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($group['created_at']))->format('%h hours %i min') }}</h3>
                            @endif
                          </div>
                          <div class="col-md-12">
                            <div class="col-md-6 text-center">
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
                            <div class="Col-md-6">
                              <h3 class="text-center">Description</h3>
                              @if ($group['type'] == 4)
                                <h3 class="text-long text-center"><b>{{ $group['description'] }}</b></h3>
                              @else
                                <h4 class="text-long text-center">{{ $group['description'] }}</h4>
                              @endif
                            </div>
                          </div>

                          <div class="col-md-12">
                            <div class="col-md-4">
                              <h3 class="text-center">Location</h3>
                              <h4 class="text-center text-long">{{ $group['address'] }}</h4>
                              <h4 class="text-center text-long">{{ $group['location'] }}</h4>
                            </div>
                            <div class="Col-md-4">
                              <h3 class="text-center">Admin</h3>
                              <h4 class="text-center">
                                <a href="/users/{{ $group['admin'] }}">
                                  <b>{{ ucfirst($group['admin']) }}</b>
                                </a>
                              </h4>
                            </div>
                            <div class="col-md-4">
                              <div class="col-md-12">
                                <h3 class="text-center">Trainers Attending:</h3>
                                <h4 align="left" class="event-members text-center">
                                  @if (count($group['users']) == 1)
                                    <h4 class="text-center">No Trainers In Group</h4>
                                  @else
                                    @foreach ($group['users'] as $users)

                                      @if ($users != $group['admin'])
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
                              <a class="btn btn-default view-group" href="/group/{{ $group['id'] }}">View Group</a>
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
    @if (count($groups['Group']))
    <div class="row controls">
      <div class="col-md-12 text-center">
        @if ($groups['Group']->previousPageUrl() != null)
          <div class="col-md-6 nav-col">
            <button class="btn btn-default prev" id="nav-button" data-url="{{$groups['Group']->previousPageUrl()}}">Prev</button>
          </div>
        @else
          <div class="col-md-6 nav-col">
            <button disabled="disabled" class="btn btn-default prev" id="nav-button" data-url="{{$groups['Group']->previousPageUrl()}}">Prev</button>
          </div>
        @endif
        @if ($groups['Group']->nextPageUrl() != null)
          <div class="col-md-6 nav-col">
            <button class="btn btn-default next" id="nav-button" data-url="{{$groups['Group']->nextPageUrl()}}">Next</button>
          </div>
        @else
          <div class="col-md-6 nav-col">
            <button disabled="disabled" class="btn btn-default next" id="nav-button" data-url="{{$groups['Group']->nextPageUrl()}}">Next</button>
          </div>
        @endif
      </div>
  </div>
  @endif
</div>
@endsection

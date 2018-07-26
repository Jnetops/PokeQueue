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
              </div>
            </div>
        </div>
      </div>
    </div>


    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel panel-heading details-heading">
          <h4 id="trainer-details-title" class="text-center">Trainer Details</h4>
        </div>

        <div class="panel panel-body details-body">

          <div class="row">
            <div class="col-md-12">

              <div class="col-md-12 trainer-details">
                <ul class="account-ul">
                  <li class="account-li">
                    <h4 id="username">Account Username: {{ ucfirst($profile['profile']->username) }}</h4>
                  </li>
                  <li class="account-li">
                    <h4 id="email">Account Email: {{ ucfirst($profile['profile']->email) }}</h4>
                  </li>
                  <li class="account-li">
                    <h4 id="trainer-name">Trainer Name: {{ ucfirst($profile['profile']->trainer_name) }}</h4>
                  </li>
                  <li class="account-li">
                    <h4 id="password-title">Account Password:</h4>
                  </li>
                  <li class="account-li">
                    <div class="col-md-10 account-col" id="col-password">
                      <h4 id="password" class="profile-info-display">***********</h4>
                    </div>
                    <div class="col-md-10 enter-password">
                      <div class="col-md-6 password">
                        <h5 id="enter-password">Password:</h5>
                      </div>
                      <div class="col-md-6 password">
                        <input id="enter-password-input" type="text"></input>
                      </div>
                    </div>

                    <div class="col-md-10 enter-password">
                      <div class="col-md-6 password">
                        <h5 id="re-enter-password">Re-Enter Password:</h5>
                      </div>
                      <div class="col-md-6 password">
                        <input id="re-enter-password-input" type="text"></input>
                      </div>
                    </div>
                    <div class="col-md-2 edit-button-col">
                      <button type="button" class="btn btn-default" id="password-edit">Edit</button>
                      <button type="button" class="btn btn-default" id="password-submit">Save</button>
                    </div>
                  </li>
                  <li class="account-li">
                    <div class="col-md-10 account-col" id="team-col">
                      @if ($profile['profile']->trainer_team == 1)
                        <h4 id="trainer-team">
                          Trainer Team: Mystic
                      @elseif ($profile['profile']->trainer_team == 2)
                        <h4 id="trainer-team">
                          Trainer Team: Valor
                      @elseif ($profile['profile']->trainer_team == 3)
                        <h4 id="trainer-team">
                          Trainer Team: Instinct
                      @endif
                        <select class="teams" id="trainer-team-select">
                          @if ($profile['profile']->trainer_team == 1)
                            <option selected="selected" value="1">Team Mystic</option>
                            <option value="2">Team Valor</option>
                            <option value="3">Team Instinct</option>
                          @elseif ($profile['profile']->trainer_team == 2)
                            <option selected="selected" value="2">Team Valor</option>
                            <option value="1">Team Mystic</option>
                            <option value="3">Team Instinct</option>
                          @elseif ($profile['profile']->trainer_team == 3)
                            <option selected="selected" value="3">Team Instinct</option>
                            <option value="1">Team Mystic</option>
                            <option value="2">Team Valor</option>
                          @endif
                        </select>
                      </h4>
                    </div>


                    <div class="col-md-2 edit-button-col" id="team-col-edit">
                      <button type="button" class="btn btn-default" id="team-edit">Edit</button>
                      <button type="button" class="btn btn-default" id="team-submit">Save</button>
                    </div>
                  </li>

                  <li class="account-li">
                    <div class="col-md-10 account-col">
                      <h4 id="trainer-level">
                        Trainer Level: {{ $profile['profile']->trainer_level }}
                        <select id="trainer-level-select">
                          <option value="{{ $profile['profile']->trainer_level }}">{{ $profile['profile']->trainer_level }}</option>
                          <option value="40">40</option>
                          <option value="39">39</option>
                          <option value="38">38</option>
                          <option value="37">37</option>
                          <option value="36">36</option>
                          <option value="35">35</option>
                          <option value="34">34</option>
                          <option value="33">33</option>
                          <option value="32">32</option>
                          <option value="31">31</option>
                          <option value="30">30</option>
                          <option value="29">29</option>
                          <option value="28">28</option>
                          <option value="27">27</option>
                          <option value="26">26</option>
                          <option value="25">25</option>
                          <option value="24">24</option>
                          <option value="23">23</option>
                          <option value="22">22</option>
                          <option value="21">21</option>
                          <option value="20">20</option>
                          <option value="19">19</option>
                          <option value="18">18</option>
                          <option value="17">17</option>
                          <option value="16">16</option>
                          <option value="15">15</option>
                          <option value="14">14</option>
                          <option value="13">13</option>
                          <option value="12">12</option>
                          <option value="11">11</option>
                          <option value="10">10</option>
                          <option value="9">9</option>
                          <option value="8">8</option>
                          <option value="7">7</option>
                          <option value="6">6</option>
                          <option value="5">5</option>
                          <option value="4">4</option>
                          <option value="3">3</option>
                          <option value="2">2</option>
                          <option value="1">1</option>
                        </select>
                      </h4>
                    </div>

                    <div class="col-md-2 edit-button-col">
                      <button type="button" class="btn btn-default" id="level-edit">Edit</button>
                      <button type="button" class="btn btn-default" id="level-submit">Save</button>
                    </div>
                  </li>
                  <li class="account-li">
                    <div class="col-md-10 account-col">
                      <h4 id="location">Location: {{ $profile['profile']->location }}</h4>
                    </div>

                    <div class="col-md-12 location-inputs text-center">
                      <div class="col-md-2">
                        <button class="btn btn-default" id="gps-button-profile" type="button"><span class="glyphicon glyphicon-map-marker"></span></button>
                      </div>
                      <div class="col-md-5 loc">
                        <select id="location-state-input">
                          <option selected="selected">Select State</option>
                          <option value="AL">Alabama</option>
                          <option value="AK">Alaska</option>
                          <option value="AZ">Arizona</option>
                          <option value="AR">Arkansas</option>
                          <option value="CA">California</option>
                          <option value="CO">Colorado</option>
                          <option value="CT">Connecticut</option>
                          <option value="DE">Delaware</option>
                          <option value="DC">District Of Columbia</option>
                          <option value="FL">Florida</option>
                          <option value="GA">Georgia</option>
                          <option value="HI">Hawaii</option>
                          <option value="ID">Idaho</option>
                          <option value="IL">Illinois</option>
                          <option value="IN">Indiana</option>
                          <option value="IA">Iowa</option>
                          <option value="KS">Kansas</option>
                          <option value="KY">Kentucky</option>
                          <option value="LA">Louisiana</option>
                          <option value="ME">Maine</option>
                          <option value="MD">Maryland</option>
                          <option value="MA">Massachusetts</option>
                          <option value="MI">Michigan</option>
                          <option value="MN">Minnesota</option>
                          <option value="MS">Mississippi</option>
                          <option value="MO">Missouri</option>
                          <option value="MT">Montana</option>
                          <option value="NE">Nebraska</option>
                          <option value="NV">Nevada</option>
                          <option value="NH">New Hampshire</option>
                          <option value="NJ">New Jersey</option>
                          <option value="NM">New Mexico</option>
                          <option value="NY">New York</option>
                          <option value="NC">North Carolina</option>
                          <option value="ND">North Dakota</option>
                          <option value="OH">Ohio</option>
                          <option value="OK">Oklahoma</option>
                          <option value="OR">Oregon</option>
                          <option value="PA">Pennsylvania</option>
                          <option value="RI">Rhode Island</option>
                          <option value="SC">South Carolina</option>
                          <option value="SD">South Dakota</option>
                          <option value="TN">Tennessee</option>
                          <option value="TX">Texas</option>
                          <option value="UT">Utah</option>
                          <option value="VT">Vermont</option>
                          <option value="VA">Virginia</option>
                          <option value="WA">Washington</option>
                          <option value="WV">West Virginia</option>
                          <option value="WI">Wisconsin</option>
                          <option value="WY">Wyoming</option>
                        </select>
                      </div>
                      <div class="col-md-5 loc">
                        <input id="location-city-input" type="text" placeholder="Enter City">
                      </div>
                    </div>

                    <div class="col-md-12 edit-button-col">
                      <button type="button" class="btn btn-default" id="location-edit">Edit</button>
                      <button type="button" class="btn btn-default" id="location-submit">Save</button>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-default friends-panel">
        <div class="panel panel-heading friends-heading">
          <h4 class="text-center">Friends ({{count($profile['friends'])}})</h4>
        </div>

        <div class="panel panel-body friends-body">
          <ul class="friends-ul">
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

        <div class="panel panel-footer friends-footer">
          <button type="button" class="btn btn-default" id="edit-friends" data-toggle="modal" data-target="#edit-friends-modal">Edit Friends</button>
        </div>
      </div>
    </div>

    <div class="col-md-6">
  		<div class="panel panel-default group-panel">
  			<div class="panel panel-heading group-heading">
  			  <h4 class="text-center">Group</h4>
  			</div>

  			<div class="panel panel-body group-body">
          <ul class="group-ul">
            @if ($profile['group'] == '')
              <li class="group-li inactive-group">
                <div class="text-center">
                  <h3 class="text-center">You Are Not In A Group</h3>
                  <button class="text-center" id="find-group"><h5><b>Find Groups</b></h5></button>
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
                    <div class="col-md-6 height">
                      <h4 class="group-location text-center">Address</h4>
                      <h4 class="group-address text-center">{{ $profile['group']['address'] }}</h4>
                      <h4 class="group-location text-center">{{ $profile['group']['location'] }}</h4>
                    </div>
                    <div class="col-md-6">
                      <h4 class="group-time text-center">Leader: <a href="/users/{{ ucfirst($profile['group']['admin']) }}">{{ ucfirst($profile['group']['admin']) }}</a></h4>
                    </div>
                    <div class="col-md-12 height">
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

    <div class="col-md-6">
      <div class="panel panel-default events-panel">
  			<div class="panel panel-heading events-heading">
  			  <h4 class="text-center">Attending Events</h4>
  			</div>
  			<div class="panel panel-body events-body">
          <ul class="events-ul">
            @if (count($profile['events']) <= 0)
              <li class="event-li inactive-event">
                <div class="text-center">
                  <h3 class="text-center">You Are Not Attending Any Events!</h3>
                  <button class="text-center" id="find-event"><h5><b>Find Events</b></h5></button>
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
                          <div class="col-md-6 height">
                            <h4 class="text-center">Address</h4>
                            <h4 class="event-address text-center">{{ $eventArray['address'] }}</h4>
                            <h4 class="event-location text-center">{{ $eventArray['location'] }}</h4>
                          </div>
                          <div class="col-md-6">
                            <h4 class="event-time text-center">Start Time: {{ \Carbon\Carbon::parse($eventArray['datetime'])->format('g:i A') }}</h4>
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
            @endif
          </ul>
  			</div>
		   </div>
    </div>


  </div>
</div>

</div>
</div>

<div class="modal fade" id="edit-friends-modal" tabindex="-1" role="dialog" aria-labelledby="edit-friends" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title text-center" id="remove-friends-title">Remove Friends</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          @if (count($profile['friends']) <= 0)
            <div class="text-center inactive">
              <h3 class="text-center">You have no friends yet!</h3>
            </div>
          @else
            @foreach ($profile['friends'] as $friend)
              <div class="col-md-6 friend-modal-col">
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
                <div class="text-center">
                   <input class="friends-checkbox" type="checkbox" value="{{$friend->trainer_name}}">
                </div>
              </div>
            @endforeach
          @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="remove-friends">Remove Friends</button>
        </div>
      </div>
      </div>
    </div>



@endsection

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PokeQueue') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/top-bar.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    @if (!Auth::guest())
      <script type="text/javascript" src="{{ URL::asset('js/pusher.min.js') }}"></script>
      <script type="text/javascript" src="{{ URL::asset('js/real-time.notifications.js') }}"></script>
      <script type="text/javascript" src="{{ URL::asset('js/top-bar.js') }}"></script>
    @endif
    @yield('scripts')
    @yield('head')
</head>
<body>
    <div id="app">
        @if (!Auth::guest())
          <input type="hidden" id="trainer" value="{{Auth::user()->trainer_name}}"></input>
          <input type="hidden" id="app-key" value="{{env("PUSHER_APP_KEY")}}"></input>
        @endif
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <b class="poke-title">{{ config('app.name', 'PokeQueue') }}</b>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a class="navbar-login" href="{{ route('login') }}">Login</a></li>
                            <li><a class="navbar-login" href="{{ route('register') }}">Register</a></li>
                        @else

                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="glyphicon glyphicon-globe"></span>
                              </a>

                              <ul class="dropdown-menu" id="location-dropdown-ul" role="menu">

                                <li class="location-li">
                                  <div class="col-md-6 gps">
                                    <div class="text-center">
                                      <h5 class="text-center">GPS</h5>
                                      <button class="btn btn-default" id="gps-button" type="button"><span class="glyphicon glyphicon-map-marker"></span></button>
                                    </div>
                                  </div>
                                  <div class="col-md-6 address">
                                    <h5 class="text-center">Adress</h5>
                                    <button type="button" class="btn btn-primary" id="locModalBtn" data-toggle="modal" data-target="#locationModal">
                                      Enter Address
                                    </button>
                                  </div>
                                </li>
                              </ul>
                            </li>

                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="glyphicon glyphicon-bell"></span>
                              </a>

                                <ul class="dropdown-menu" role="menu" id="notifications-ul">
                                  @if (Auth::user()->unreadNotifications->isEmpty())
                                    <li class="notification-li">
                                      <div class="notification-item text-center">
                                        <h3 id="no-notifications">No New Noticiations</h3>
                                      </div>
                                    </li>
                                  @else
                                    @foreach (Auth::user()->unreadNotifications as $notification)
                                      @if ($notification->type == 'App\Notifications\CreateEvent')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item event panel text-center panel-body" id="{{ $decodedArray['event_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>{{ $decodedArray['date'] }} </b> </h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="event btn btn-default notification" id="{{ $decodedArray['event_id'] }}">View Event</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\CreateGroup')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item group panel text-center panel-body" id="{{ $decodedArray['group_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>
                                              @foreach ($decodedArray['types'] as $type)
                                                {{$type}}
                                              @endforeach
                                             </b> </h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="group btn btn-default notification" id="{{ $decodedArray['group_id'] }}">View Group</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\EventDisbanded')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item panel panel-body text-center">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}} </b> </h5>
                                            <div class="col-md-12 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\EventInviteAccepted')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item event panel text-center panel-body" id="{{ $decodedArray['event_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}} </b> {{$decodedArray['statement2']}}<b>{{$decodedArray['host']}} </b> </h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="event btn btn-default notification" id="{{ $decodedArray['event_id'] }}">View Event</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\EventInviteRequest')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item event panel text-center panel-body" id="{{ $decodedArray['event_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}} </b> </h5>
                                            <input type="hidden" class="mark-read btn btn-default notification" id="{{$notification->id}}"></input>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="accept-event-invite" data-user="{{ $decodedArray['user'] }}" data-id="{{$decodedArray['event_id']}}">Accept Request</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="reject-event-invite" data-user="{{ $decodedArray['user'] }}" data-id="{{$decodedArray['event_id']}}">Reject Request</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="event btn btn-default notification" id="{{ $decodedArray['event_id'] }}">View Event</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\EventInviteSend')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item event panel text-center panel-body" id="{{ $decodedArray['event_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}} </b> </h5>
                                            <input type="hidden" class="mark-read btn btn-default notification" id="{{$notification->id}}"></input>
                                            <div class="col-md-4 notification">
                                              <button class="event btn btn-default notification" id="{{ $decodedArray['event_id'] }}">View Event</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="accept-event-invite" data-id="{{$decodedArray['event_id']}}">Accept Invite</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="reject-event-invite" data-id="{{$decodedArray['event_id']}}">Reject Invite</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\EventStartingSoon')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item event panel text-center panel-body" id="{{ $decodedArray['event_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['admin'] }}'s </b> {{ $decodedArray['statement'] }}<b>{{$decodedArray['date']}}! </b> </h5>
                                            <div class="col-md-6 notification">
                                              <button class="event btn btn-default notification" id="{{ $decodedArray['event_id'] }}">View Event</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\GroupDisbanded')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item panel panel-body text-center">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}</h5>
                                            <div class="col-md-12 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\GroupFinalized')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item group panel text-center panel-body" id="{{ $decodedArray['group_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b></h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="group btn btn-default notification" id="{{ $decodedArray['group_id'] }}">View Group</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\GroupInviteSend')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item group panel text-center panel-body" id="{{ $decodedArray['group_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>
                                              @foreach ($decodedArray['types'] as $type)
                                                {{$type}}
                                              @endforeach
                                             </b> </h5>
                                            <input type="hidden" class="mark-read btn btn-default notification" id="{{$notification->id}}"></input>
                                            <div class="col-md-4 notification">
                                              <button class="group btn btn-default notification" id="{{ $decodedArray['group_id'] }}">View Group</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="accept-group-invite" data-id="{{$decodedArray['group_id']}}">Accept Invite</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="reject-group-invite" data-id="{{$decodedArray['group_id']}}">Reject Invite</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\GroupInviteRequest')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item group panel text-center panel-body" id="{{ $decodedArray['group_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}</h5>
                                            <input type="hidden" class="mark-read btn btn-default notification" id="{{$notification->id}}"></input>
                                            <div class="col-md-4 notification">
                                              <button class="group btn btn-default notification" id="{{ $decodedArray['group_id'] }}">View Group</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="accept-group-invite" data-user="{{ $decodedArray['user'] }}" data-id="{{$decodedArray['group_id']}}">Accept Request</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="reject-group-invite" data-user="{{ $decodedArray['user'] }}" data-id="{{$decodedArray['group_id']}}">Reject Request</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\GroupInviteAccepted')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item group panel text-center panel-body" id="{{ $decodedArray['group_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}{{$decodedArray['statement2']}}<b>{{$decodedArray['host']}} </b> </h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="group btn btn-default notification" id="{{ $decodedArray['group_id'] }}">View Group</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\PokeTrackerAdd')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item poketracker panel text-center panel-body" id="{{ $decodedArray['tracker_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}<b>{{$decodedArray['pokemon']}} </b> {{$decodedArray['statement2']}}</h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="poketracker btn btn-default notification" id="{{ $decodedArray['tracker_id'] }}">View Pokemon</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\AcceptedFriendRequest')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item friend panel text-center panel-body" id="{{ $decodedArray['user'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}</h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="friend btn btn-default notification" id="{{ $decodedArray['user'] }}">View User</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\ReceivedFriendRequest')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item friend panel text-center panel-body" id="{{ $decodedArray['user'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}</h5>
                                            <input type="hidden" class="mark-read btn btn-default notification" id="{{$notification->id}}"></input>
                                            <div class="col-md-4 notification">
                                              <button class="friend btn btn-default notification" id="{{ $decodedArray['user'] }}">View User</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="accept-friend-request" data-user="{{ $decodedArray['user'] }}">Accept Request</button>
                                            </div>
                                            <div class="col-md-4 notification">
                                              <button class="btn btn-default notification" id="reject-friend-request" data-user="{{ $decodedArray['user'] }}">Reject Request</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @elseif ($notification->type == 'App\Notifications\RaidTrackerAdd')
                                        @foreach ($notification->data as $decodedArray)
                                        <li class="notification-li">
                                          <div class="notification-item raidtracker panel text-center panel-body" id="{{ $decodedArray['tracker_id'] }}">
                                            <h5 class="notify-h5 text-center"><b>{{ $decodedArray['user'] }} </b> {{ $decodedArray['statement'] }}</h5>
                                            <div class="col-md-6 notification">
                                              <button class="mark-read btn btn-default notification" id="{{$notification->id}}">Mark As Read</button>
                                            </div>
                                            <div class="col-md-6 notification">
                                              <button class="raidtracker btn btn-default notification" id="{{ $decodedArray['tracker_id'] }}">View Raid</button>
                                            </div>
                                          </div>
                                        </li>
                                        @endforeach
                                      @endif
                                    @endforeach
                                  @endif
                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ ucfirst(Auth::user()->username) }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                      <a class="dropdown-a" href="/profile">Profile</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-a" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        @endif
                    </ul>
                    @if (!Auth::guest())
                    <ul class="nav navbar-nav navbar-left">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                          Groups<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                          <li>
                            <a class="dropdown-a" href="/group/create">Create Group</a>
                          </li>
                          <li>
                            <a class="dropdown-a" href="/groups?distance=25">View Groups</a>
                          </li>
                        </ul>

                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                          Events<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                          <li>
                            <a class="dropdown-a" href="/events/create">Create Event</a>
                          </li>
                          <li>
                            <a  class="dropdown-a" href="/events?distance=25">View Events</a>
                          </li>
                        </ul>

                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                          PokeTracker<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                          <li>
                            <a class="dropdown-a" href="/poke/tracker/add">Add Pokemon</a>
                          </li>
                          <li>
                            <a class="dropdown-a" href="/poke/tracker?distance=25">View PokeTracker</a>
                          </li>
                        </ul>

                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle nav-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                          RaidTracker<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                          <li>
                            <a class="dropdown-a" href="/raid/tracker/add">Add Raid</a>
                          </li>
                          <li>
                            <a class="dropdown-a" href="/raid/tracker?distance=25">View RaidTracker</a>
                          </li>
                        </ul>

                      </li>
                    </ul>
                    @endif

                </div>
            </div>
        </nav>
        <!-- Modal -->
        <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header location text-center">
                <h5 class="modal-title location" id="locationModelLabel">Enter Address</h5>
                <button type="button" class="close location" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body location">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <input id="location-input-address" type="text" placeholder="Enter Address">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="col-md-6 text-center">
                    <input id="location-input-city" type="text" placeholder="Enter City">
                  </div>
                  <div class="col-md-6 text-center">
                    <select id="location-input-state">
                      <option selected="selected">Select State</option> <option value="AL">Alabama</option> <option value="AK">Alaska</option> <option value="AZ">Arizona</option> <option value="AR">Arkansas</option> <option value="CA">California</option> <option value="CO">Colorado</option> <option value="CT">Connecticut</option> <option value="DE">Delaware</option> <option value="DC">District Of Columbia</option> <option value="FL">Florida</option> <option value="GA">Georgia</option> <option value="HI">Hawaii</option> <option value="ID">Idaho</option> <option value="IL">Illinois</option> <option value="IN">Indiana</option> <option value="IA">Iowa</option> <option value="KS">Kansas</option> <option value="KY">Kentucky</option> <option value="LA">Louisiana</option> <option value="ME">Maine</option> <option value="MD">Maryland</option> <option value="MA">Massachusetts</option> <option value="MI">Michigan</option> <option value="MN">Minnesota</option> <option value="MS">Mississippi</option> <option value="MO">Missouri</option> <option value="MT">Montana</option> <option value="NE">Nebraska</option> <option value="NV">Nevada</option> <option value="NH">New Hampshire</option> <option value="NJ">New Jersey</option> <option value="NM">New Mexico</option> <option value="NY">New York</option> <option value="NC">North Carolina</option> <option value="ND">North Dakota</option> <option value="OH">Ohio</option> <option value="OK">Oklahoma</option> <option value="OR">Oregon</option> <option value="PA">Pennsylvania</option> <option value="RI">Rhode Island</option> <option value="SC">South Carolina</option> <option value="SD">South Dakota</option> <option value="TN">Tennessee</option> <option value="TX">Texas</option> <option value="UT">Utah</option> <option value="VT">Vermont</option> <option value="VA">Virginia</option> <option value="WA">Washington</option> <option value="WV">West Virginia</option> <option value="WI">Wisconsin</option> <option value="WY">Wyoming</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="modal-footer location">
                <div class="col-md-6">
                  <button type="button" class="btn btn-secondary" id="close-modal" data-dismiss="modal">Close</button>
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-primary" id="submit-location">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @include('footer') {{-- Include footer file --}}
</body>
</html>

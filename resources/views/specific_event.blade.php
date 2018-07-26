@extends('layouts.app')


<link href="{{ asset('css/specific_event.css') }}" rel="stylesheet">

@section('scripts')
	<script type="text/javascript" src="{{ URL::asset('js/event.js') }}"></script>
	@if ($events['Event']['inEvent'] == true)
		<script type="text/javascript" src="{{ URL::asset('js/real-time.event.auth.js') }}"></script>
	@else
		<script type="text/javascript" src="{{ URL::asset('js/real-time.event.js') }}"></script>
	@endif
@endsection

@section('head')
	<meta property="og:url"           content="{{Request::url()}}{{Request::getQueryString()}}">
  <meta property="og:type"          content="website">
	<meta property="fb:app_id"        content="na">
	<meta property="og:image:width"   content="200">
	<meta property="og:image:height"   content="200">
	@if ($events['Event']['event']['type'] == 1)
		@if ($events['Event']['event']['subType1'] == 1)
			<meta property="og:image"         content="{{url('/images/mystic-battles.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Gym Battles">
		  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
		@elseif ($events['Event']['event']['subType1'] == 2)
			<meta property="og:image"         content="{{url('/images/valor-battles.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Gym Battles">
		  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
		@elseif ($events['Event']['event']['subType1'] == 3)
			<meta property="og:image"         content="{{url('/images/instinct-battles.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Gym Battles">
		  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
		@endif
	@elseif ($events['Event']['event']['type'] == 2)
		@if ($events['Event']['event']['subType1'] == 1)
			<meta property="og:image"         content="{{url('/images/event-farming.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Event Farming">
		  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
		@elseif ($events['Event']['event']['subType1'] == 2)
			<meta property="og:image"         content="{{ URL::to('/') }}/images/sprites/{{$events['Event']['event']['subType2']}}.jpg">
			<meta property="og:title"         content="PokeQueue - Nest Farming">
		  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
		@elseif ($events['Event']['event']['subType1'] == 3)
			<meta property="og:image"         content="{{url('/images/maxresdefault.jpg')}}">
			<meta property="og:title"         content="PokeQueue - General Farming">
		  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
		@endif
	@elseif ($events['Event']['event']['type'] == 3)
		<meta property="og:image"         content="{{url('/images/farming-all.jpg')}}">
		<meta property="og:title"         content="PokeQueue - Item Farming">
	  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
	@elseif ($events['Event']['event']['type'] == 4)
		<meta property="og:image"         content="{{ URL::to('/') }}/images/sprites/{{$events['Event']['event']['subType2']}}.jpg">
		<meta property="og:title"         content="PokeQueue - Raid Group">
	  <meta property="og:description"   content="{{ $events['Event']['event']['description'] }}">
	@endif
@endsection

@section('content')
<input type="hidden" value="event" id="page-type">
@if ($events['Event']['User'] == $events['Event']['event']['admin'])
	<input type="hidden" id="status" value="admin"></input>
@elseif (in_array($events['Event']['User'], $events['Event']['event']['users']))
	<input type="hidden" id="status" value="user"></input>
@else
	<input type="hidden" id="status" value="non-user"></input>
@endif

<input type="hidden" id="event-id" value="{{$events['Event']['event']['id']}}"></input>


<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-12">
        <div class="panel panel-default status-panel">
          <div class="panel-heading">
            <h5 class="event-time text-center">Event Date</h5>
            <h3 class="event-time text-center">{{ \Carbon\Carbon::parse($events['Event']['event']['datetime'])->format('F j, Y g:i A') }} {{\Carbon\Carbon::now()->setTimezone($events['Event']['event']['timezone'])->format('T')}}</h3>
          </div>
		  @if ($events['Event']['User'] != "No")
					<div class="panel-footer clearfix">
						@if ($events['Event']['inEvent'] == true)
							@if ($events['Event']['User'] == $events['Event']['event']['admin'])
								<button class="btn btn-default add-event" disabled>Admin</button>
							@else
								<button class="btn btn-default add-event" disabled>Joined</button>
							@endif
						@elseif ($events['Event']['inEvent'] == false)
							@if ($events['Event']['invited'] == true)
								<button class="btn btn-default add-event accept-invite" id="{{$events['Event']['event']['id']}}">Invited | Accept</button>
							@elseif ($events['Event']['requested'] == true)
								<button class="btn btn-default add-event" disabled>Requested</button>
							@else
								<button class="btn btn-default add-event request-invite" id="{{$events['Event']['event']['id']}}">Join Event</button>
							@endif
						@endif
					</div>
		@else
			<div class="panel-footer clearfix">
				<button class="btn btn-default login">Login</button>
				<p class="login-p">Login To Join Event</p>
			</div>
		@endif
        </div>
      </div>

        @if ($events['Event']['inEvent'] == true)
          <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading chat-heading text-center">
                    Event Chat
                </div>

                <div class="panel-body chat-panel-body">
                  <ul class="chat-list-event" id="chat-panel">
                    @foreach ($events['Event']['Chat'] as $chat)
                      <li class="li-event-item">
                        <div class="col-md-12 chat-col">
                          <div class="col-md-1 text-center">
                            @if ($chat['trainer_team'] == 1)
                              <div class="profile-avatar tiny text-center" style="background-image: url('/images/mystic.png')"></div>
                            @elseif ($chat['trainer_team'] == 2)
                              <div class="profile-avatar tiny text-center" style="background-image: url('/images/valor.png')"></div>
                            @elseif ($chat['trainer_team'] == 3)
                              <div class="profile-avatar tiny text-center" style="background-image: url('/images/instinct.png')"></div>
                            @endif
                          </div>
                          <div class="col-md-9">
                            <a class="trainer-name" href="/users/{{ ucfirst($chat['trainer_name']) }}">{{ ucfirst($chat['trainer_name']) }}</a>
                            <p>{{ ucfirst($chat['comment']) }}</p>
                          </div>
                          <div class="col-md-2">
                            <p>{{ \Carbon\Carbon::parse($chat['created_at'])->setTimezone($events['Event']['event']['timezone'])->format('g:i A T') }}</p>
                          </div>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div class="panel-footer ">
                  <input minlength="1" maxlength="255" name="message" type="text" id="event-chat-comment" class="chat-message">
                  <button type="button" id="event-chat-submit" class="btn btn-primary chat-message-submit">Submit</button>
                </div>
            </div>
          </div>
        @else
          <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    Event Chat
                    <p>You Must Be A Member Of This Event To Unlock This Feature</p>
                </div>
            </div>
          </div>
        @endif
        <div class="col-md-6">
          <div id="type">
						@if ($events['Event']['event']['type'] == 1)
							@if ($events['Event']['event']['subType1'] == 1)
								<img src="{{url('/images/mystic-battles.jpg')}}" alt="Image" class="event-image"/>
							@elseif ($events['Event']['event']['subType1'] == 2)
								<img src="{{url('/images/valor-battles.jpg')}}" alt="Image" class="event-image"/>
							@elseif ($events['Event']['event']['subType1'] == 3)
								<img src="{{url('/images/instinct-battles.jpg')}}" alt="Image" class="event-image"/>
							@endif
						@elseif ($events['Event']['event']['type'] == 2)
							@if ($events['Event']['event']['subType1'] == 1)
								<img src="{{url('/images/event-farming.jpg')}}" alt="Image" class="event-image"/>
							@elseif ($events['Event']['event']['subType1'] == 2)
								<div class="col-md-12 poke-catch" style="background-image: url('/images/poke-catch.jpg');">
									<img src="{{ URL::to('/') }}/images/sprites/{{$events['Event']['event']['subType2']}}.png" alt="Image" class="event-image pokemon"/>
									<div class="text-center">
										<h1><b>Nest Farming</b></h1>
									</div>
								</div>
							@elseif ($events['Event']['event']['subType1'] == 3)
								<img src="{{url('/images/farming-all.jpg')}}" alt="Image" class="event-image"/>
							@endif
						@elseif ($events['Event']['event']['type'] == 3)
							<img src="{{url('/images/item-farming.jpg')}}" alt="Image" class="event-image"/>
						@endif
          </div>

					<div class="panel panel-default information">
              <div class="panel-body info-body">
								<div class="col-md-12 iframe-col text-center">
									<iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button&size=large&mobile_iframe=true&appId=na&width=72&height=28" width="72" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
								</div>
								<div class="col-md-6 location-col">
	                <h4 class="text-center">Location:</h4>
	                <h4 class="text-center" id="location">{{ $events['Event']['event']['address'] }}</h4>
	                <h4 class="text-center" id="location">{{ $events['Event']['event']['location'] }}</h4>
								</div>
								<div class="col-md-6 description-col">
									<h4 class="text-center"><b>Description:</b></h4>
									<h5 class="text-center" id="description">{{ $events['Event']['event']['description'] }}</h5>
								</div>
              </div>
          </div>
        </div>

        <div class="col-md-6">
          @if ($events['Event']['User'] == $events['Event']['event']['admin'])
            <div class="col-md-12 user-buttons">
              <button type="button" class="btn btn-default" id="invite-button" data-toggle="modal" data-target="#event-invite-modal">Invite To Event</button>
            </div>
            <div class="col-md-12 user-buttons">
              <button type="button" class="btn btn-default" id="transfer-admin-button" data-toggle="modal" data-target="#transfer-admin-modal">Transfer Admin</button>
            </div>
          @elseif (in_array($events['Event']['User'], $events['Event']['event']['users']))
					<div class="col-md-12 user-buttons">
						<button type="button" class="btn btn-default" id="leave-event-button">Leave Event</button>
					</div>
          @endif
          <div class="row">
            <div class="col-md-6" id="user-list-col">
              <div class="panel panel-default">
                <div class="panel-body users">
                  <ul id="user-list">
										@foreach ($events['Event']['UserData'] as $user)
                      @if ($user->trainer_name == $events['Event']['event']['admin'])
                    		@if ($user->trainer_team == 1)

													<li class="user-li admin">
														<div class="col-md-12 admin-title text-center">
															<h4 class="admin-title-h4">Administrator</h4>
														</div>
													  <a href="/users/{{$user->trainer_name}}">
														  <div class="col-md-12 team-image" style="background-image: url('/images/mystic.png'); background-color: 	#000;">
														  <h5 id="trainer-level-display">Level {{ $user->trainer_level }}</h5>
														  <h5 id="trainer-name-display">{{ucfirst($user->trainer_name)}}</h5>
														</div>
													  </a>
													</li>

												@elseif ($user->trainer_team == 2)
													<li class="user-li admin">
														<div class="col-md-12 admin-title text-center">
															<h4 class="admin-title-h4">Administrator</h4>
														</div>
													  <a href="/users/{{$user->trainer_name}}">
														  <div class="col-md-12 team-image" style="background-image: url('/images/valor.png'); background-color: 	#000;">
														  <h5 id="trainer-level-display">Level {{ $user->trainer_level }}</h5>
														  <h5 id="trainer-name-display">{{ucfirst($user->trainer_name)}}</h5>
														</div>
													  </a>
													</li>
												@elseif ($user->trainer_team == 3)
													<li class="user-li admin">
														<div class="col-md-12 admin-title text-center">
															<h4 class="admin-title-h4">Administrator</h4>
														</div>
													  <a href="/users/{{$user->trainer_name}}">
														  <div class="col-md-12 team-image" style="background-image: url('/images/instinct.png'); background-color: 	#000;">
														  <h5 id="trainer-level-display">Level {{ $user->trainer_level }}</h5>
														  <h5 id="trainer-name-display">{{ucfirst($user->trainer_name)}}</h5>
														</div>
													  </a>
													</li>
												@endif
                      @endif
                    @endforeach
                    @foreach ($events['Event']['UserData'] as $user)
                      @if ($user->trainer_team == 1 && $user->trainer_name != $events['Event']['event']['admin'])
	                    	<li class="user-li">
	                    	  <a href="/users/{{$user->trainer_name}}">
	                    		  <div class="col-md-12 team-image" style="background-image: url('/images/mystic.png'); background-color: 	#000;">
	                    		  <h5 id="trainer-level-display">Level {{ $user->trainer_level }}</h5>
	                    		  <h5 id="trainer-name-display">{{ucfirst($user->trainer_name)}}</h5>
	                    		</div>
	                    	  </a>
	                    	</li>
                      @endif
                    @endforeach
                    @foreach ($events['Event']['UserData'] as $user)
                      @if ($user->trainer_team == 2 && $user->trainer_name != $events['Event']['event']['admin'])
	                      <li class="user-li">
	                    	  <a href="/users/{{$user->trainer_name}}">
	                    		  <div class="col-md-12 team-image" style="background-image: url('/images/valor.png'); background-color: 	#000;">
	                    		  <h5 id="trainer-level-display">Level {{ $user->trainer_level }}</h5>
	                    		  <h5 id="trainer-name-display">{{ucfirst($user->trainer_name)}}</h5>
	                    		</div>
	                    	  </a>
	                    	</li>
                      @endif
                    @endforeach
                    @foreach ($events['Event']['UserData'] as $user)
                      @if ($user->trainer_team == 3 && $user->trainer_name != $events['Event']['event']['admin'])
	                    	<li class="user-li">
	                    	  <a href="/users/{{$user->trainer_name}}">
	                    		  <div class="col-md-12 team-image" style="background-image: url('/images/instinct.png'); background-color: 	#000;">
	                    		  <h5 id="trainer-level-display">Level {{ $user->trainer_level }}</h5>
	                    		  <h5 id="trainer-name-display">{{ucfirst($user->trainer_name)}}</h5>
	                    		</div>
	                    	  </a>
	                    	</li>
                      @endif
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

    </div>

    @if ($events['Event']['User'] == $events['Event']['event']['admin'])
      <div class="col-md-12 event-buttons-col">
        <div class="col-md-6 event-buttons">
          <button type="button" class="btn btn-default" id="finalize-button" data-toggle="modal" data-target="#event-finalize-confirm-modal">Finalize Event</button>
        </div>
        <div class="col-md-6 event-buttons">
          <button type="button" class="btn btn-default" id="disband-button" data-toggle="modal" data-target="#event-disband-confirm-modal">Disband Event</button>
        </div>
      </div>
    @endif

  </div>
</div>



@if ($events['Event']['User'] == $events['Event']['event']['admin'])
  <div class="modal fade" id="transfer-admin-modal" tabindex="-1" role="dialog" aria-labelledby="transfer-admin" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="modal-title text-center" id="transfer-admin-modal-title">Transfer Admin: <h5 class="text-center" id="transfer-error"></h5></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="btn-event text-center" id="transfer-admin-radio" value="{{ $events['Event']['event']['id'] }}">
            @foreach ($events['Event']['event']['users'] as $users)
              @if ($users != $events['Event']['event']['admin'])
                <div class="radio">
                  <label><input type="radio" name="trainer" value="{{ $users }}">{{ $users }}</label>
                </div>
              @endif
            @endforeach

            @if (count($events['Event']['event']['users']) == 1)
              <h5 class="text-center">No Users Available To Transfer Admin, Invite Additional Users</h5>
            @endif
          </div>
        </div>
        <div class="modal-footer">
					<div class="col-md-6">
          	<button type="button" class="btn btn-primary" id="transfer-admin-save">Save changes</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="event-invite-modal" tabindex="-1" role="dialog" aria-labelledby="event-invite" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="modal-title text-center" id="event-invite-modal-title">Invite To Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Trainer Name: <input type="text" name="trainer" id="trainer-name-input"></label>
        </div>
        <div class="modal-footer">
					<div class="col-md-6">
          	<button type="button" class="btn btn-primary" id="event-invite-request">Send Request</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="event-disband-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="event-disband" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Are you sure you want to disband the event?:</h5>
        </div>
        <div class="modal-body">
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm-disband-event">Yes</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					</div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="event-finalize-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="event-finalize" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Are you sure you want to finalize the event and leave the event queue?:</h5>
        </div>
        <div class="modal-body">
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm-finalize-event">Yes</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					</div>
        </div>
      </div>
    </div>
  </div>

@elseif (in_array($events['Event']['User'], $events['Event']['event']['users']))

@else

@endif

@endsection

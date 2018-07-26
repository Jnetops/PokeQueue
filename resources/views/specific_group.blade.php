@extends('layouts.app')

@section('scripts')
	<link href="{{ asset('css/specific_group.css') }}" rel="stylesheet">

	<script type="text/javascript" src="{{ URL::asset('js/group.js') }}"></script>
	@if ($groups['inGroup'] == true)
		<script type="text/javascript" src="{{ URL::asset('js/real-time.group.auth.js') }}"></script>
	@else
		<script type="text/javascript" src="{{ URL::asset('js/real-time.group.js') }}"></script>
	@endif
@endsection

@section('head')
	<meta property="og:url"           content="{{Request::url()}}">
  <meta property="og:type"          content="website">
	<meta property="fb:app_id"        content="118276168868059">
	@if ($groups['Group']['type'] == 1)
		@if ($groups['Group']['subType1'] == 1)
			<meta property="og:image"         content="{{url('/images/mystic-battles.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Gym Battles">
		  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
		@elseif ($groups['Group']['subType1'] == 2)
			<meta property="og:image"         content="{{url('/images/valor-battles.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Gym Battles">
		  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
		@elseif ($groups['Group']['subType1'] == 3)
			<meta property="og:image"         content="{{url('/images/instinct-battles.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Gym Battles">
		  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
		@endif
	@elseif ($groups['Group']['type'] == 2)
		@if ($groups['Group']['subType1'] == 1)
			<meta property="og:image"         content="{{url('/images/event-farming.jpg')}}">
			<meta property="og:title"         content="PokeQueue - Event Farming">
		  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
		@elseif ($groups['Group']['subType1'] == 2)
			<meta property="og:image"         content="{{ URL::to('/') }}/images/sprites/{{$groups['Group']['subType2']}}.jpg">
			<meta property="og:title"         content="PokeQueue - Nest Farming">
		  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
		@elseif ($groups['Group']['subType1'] == 3)
			<meta property="og:image"         content="{{url('/images/farming-all.jpg')}}">
			<meta property="og:title"         content="PokeQueue - General Farming">
		  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
		@endif
	@elseif ($groups['Group']['type'] == 3)
		<meta property="og:image"         content="{{url('/images/item-farming.jpg')}}">
		<meta property="og:title"         content="PokeQueue - Item Farming">
	  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
	@elseif ($groups['Group']['type'] == 4)
		<meta property="og:image"         content="{{ URL::to('/') }}/images/sprites/{{$groups['Group']['subType2']}}.jpg">
		<meta property="og:title"         content="PokeQueue - Raid Group">
	  <meta property="og:description"   content="{{ $groups['Group']['description'] }}">
	@endif
@endsection



@section('content')

<input type="hidden" value="group" id="page-type">
@if ($groups['User'] == $groups['Group']['admin'])
	<input type="hidden" id="status" value="admin"></input>
@elseif (in_array($groups['User'], $groups['Group']['users']))
	<input type="hidden" id="status" value="user"></input>
@else
	<input type="hidden" id="status" value="non-user"></input>
@endif

<input type="hidden" id="group-id" value="{{$groups['Group']['id']}}"></input>
<input type="hidden" id="state" value="{{$groups['Group']['status']}}"></input>


<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-12">
        <div class="panel panel-default status-panel">
          <div class="panel-heading text-center">
            @if ($groups['Group']['status'] == 'queued')
              <h4 class="text-center">Status: Queued</h4>
              @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($groups['Group']['created_at']))->format('%H') == '00')
                <h4 class="group-time text-center">Queue Time: </h4>
                <h4 id="group-time" class="group-time text-center">{{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($groups['Group']['created_at']))->format('%i min %s sec') }}</h4>
              @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($groups['Group']['created_at']))->format('%H') == '01')
                <h4 class="group-time text-center">Queue Time: </h4>
                <h4 id="group-time" class="group-time text-center">{{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($groups['Group']['created_at']))->format('%h hour %i min %s sec') }}</h4>
              @else
                <h4 class="group-time text-center">Queue Time: </h4>
                <h4 id="group-time" class="group-time text-center">{{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($groups['Group']['created_at']))->format('%h hours %i min %s sec') }}</h4>
              @endif
            @else
              <h4 class="group-time text-center">Status: Finalized</h4>
            @endif
          </div>
		  @if ($groups['User'] != "No")
					<div class="panel-footer clearfix">
						@if ($groups['inGroup'] == true)
							@if ($groups['User'] == $groups['Group']['admin'])
								<button class="btn btn-default add-group" disabled>Admin</button>
							@else
								<button class="btn btn-default add-group" disabled>Joined</button>
							@endif
						@elseif ($groups['inGroup'] == false)
							@if ($groups['invited'] == true)
								<button class="btn btn-default add-group accept-invite" id="{{$groups['Group']['id']}}">Invited | Accept</button>
							@elseif ($groups['requested'] == true)
								<button class="btn btn-default add-group" disabled>Requested</button>
							@else
								<button class="btn btn-default add-group request-invite" id="{{$groups['Group']['id']}}">Join Group</button>
							@endif
						@endif
					</div>
		@else
			<div class="panel-footer clearfix">
				<button class="btn btn-default login">Login</button>
				<p class="login-p">Login To Join Group</p>
			</div>
		@endif
        </div>
      </div>
        @if ($groups['inGroup'] == true)
          <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading chat-heading text-center">
                    Group Chat
                </div>

                <div class="panel-body chat-panel-body">
                  <ul class="chat-list-group" id="chat-panel">
                    @foreach ($groups['Chat'] as $chat)
                      <li class="li-group-item">
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
                          <div class="col-md-2 text-right">
                            <p>{{ \Carbon\Carbon::parse($chat['created_at'])->setTimezone($groups['Group']['timezone'])->format('g:i A T') }}</p>
                          </div>
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>

                <div class="panel-footer ">
                  <input minlength="1" maxlength="255" name="message" type="text" id="group-chat-comment" class="chat-message">
                  <button type="button" id="group-chat-submit" class="btn btn-primary chat-message-submit">Submit</button>
                </div>
            </div>
          </div>
        @else
          <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    Group Chat
                    <p>You Must Be A Member Of This Group To Unlock This Feature</p>
                </div>
            </div>
          </div>
        @endif
        <div class="col-md-6 text-center">
          <div id="type">
						@if ($groups['Group']['type'] == 1)
							@if ($groups['Group']['subType1'] == 1)
								<img src="{{url('/images/mystic-battles.jpg')}}" alt="Image" class="group-image"/>
							@elseif ($groups['Group']['subType1'] == 2)
								<img src="{{url('/images/valor-battles.jpg')}}" alt="Image" class="group-image"/>
							@elseif ($groups['Group']['subType1'] == 3)
								<img src="{{url('/images/instinct-battles.jpg')}}" alt="Image" class="group-image"/>
							@endif
						@elseif ($groups['Group']['type'] == 2)
							@if ($groups['Group']['subType1'] == 1)
								<img src="{{url('/images/event-farming.jpg')}}" alt="Image" class="group-image"/>
							@elseif ($groups['Group']['subType1'] == 2)
								<div class="col-md-12 poke-catch" style="background-image: url('/images/poke-catch.jpg');">
									<img src="{{ URL::to('/') }}/images/sprites/{{$groups['Group']['subType2']}}.png" alt="Image" class="group-image-pokemon"/>
									<div class="text-center">
										<h1><b>Nest Farming</b></h1>
									</div>
								</div>
							@elseif ($groups['Group']['subType1'] == 3)
								<img src="{{url('/images/farming-all.jpg')}}" alt="Image" class="group-image"/>
							@endif
						@elseif ($groups['Group']['type'] == 3)
							<img src="{{url('/images/item-farming.jpg')}}" alt="Image" class="group-image"/>
						@elseif ($groups['Group']['type'] == 4)
							<img src="{{ URL::to('/') }}/images/sprites/{{$groups['Group']['subType2']}}.png" alt="Image" class="raid-group-image"/>
						@endif
          </div>

          <div class="panel panel-default information">
              <div class="panel-body info-body">
								<div class="col-md-12 iframe-col text-center">
									<iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button&size=large&mobile_iframe=true&appId=na&width=72&height=28" width="72" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
								</div>
								<div class="col-md-6 location-col">
	                <h4 class="text-center">Location:</h4>
	                <h4 class="text-center" id="location">{{ $groups['Group']['address'] }}</h4>
	                <h4 class="text-center" id="location">{{ $groups['Group']['location'] }}</h4>
								</div>
								<div class="col-md-6 description-col">
									<h4 class="text-center"><b>Description:</b></h4>
									@if ($groups['Group']['type'] == 4)
                              			<h3 align="center" class="group-description"><b>{{ $groups['Group']['description'] }}</b></h3>
                            		@else
                              			<h5 class="text-center" id="description">{{ $groups['Group']['description'] }}</h5>
                            		@endif
								</div>
              </div>
          </div>
        </div>

        <div class="col-md-6">
          @if ($groups['User'] == $groups['Group']['admin'] && $groups['Group']['status'] != 'finalized')
            <div class="col-md-12 user-buttons">
              <button type="button" class="btn btn-default" id="invite-button" data-toggle="modal" data-target="#group-invite-modal">Invite To Group</button>
            </div>
            <div class="col-md-12 user-buttons">
              <button type="button" class="btn btn-default" id="transfer-admin-button" data-toggle="modal" data-target="#transfer-admin-modal">Transfer Admin</button>
            </div>
          @elseif (in_array($groups['User'], $groups['Group']['users']) && $groups['User'] != $groups['Group']['admin'])
						<button type="button" class="btn btn-default" id="leave-group-button">Leave Group</button>
          @endif
          <div class="row">
            <div class="col-md-6" id="user-list-col">
              <div class="panel panel-default">
                <div class="panel-body users">
                  <ul id="user-list">
										@foreach ($groups['UserData'] as $user)
                      @if ($user->trainer_name == $groups['Group']['admin'])
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
                    @foreach ($groups['UserData'] as $user)
                      @if ($user->trainer_team == 1 && $user->trainer_name != $groups['Group']['admin'])
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
                    @foreach ($groups['UserData'] as $user)
                      @if ($user->trainer_team == 2 && $user->trainer_name != $groups['Group']['admin'])
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
                    @foreach ($groups['UserData'] as $user)
                      @if ($user->trainer_team == 3 && $user->trainer_name != $groups['Group']['admin'])
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

    @if ($groups['User'] == $groups['Group']['admin'])
      <div class="col-md-12 group-buttons-col">
        @if ($groups['Group']['status'] != 'finalized')
          <div class="col-md-6 group-buttons">
            <button type="button" class="btn btn-default" id="finalize-button" data-toggle="modal" data-target="#group-finalize-confirm-modal">Finalize Group</button>
          </div>
        @else
          <div class="col-md-6 group-buttons">
            <button type="button" class="btn btn-default" id="queue-button" data-toggle="modal" data-target="#group-queue-confirm-modal">Requeue Group</button>
          </div>
        @endif
        <div class="col-md-6 group-buttons">
          <button type="button" class="btn btn-default" id="disband-button" data-toggle="modal" data-target="#group-disband-confirm-modal">Disband Group</button>
        </div>
      </div>
    @endif

  </div>
</div>



@if ($groups['User'] == $groups['Group']['admin'])
  <div class="modal fade" id="transfer-admin-modal" tabindex="-1" role="dialog" aria-labelledby="transfer-admin" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h5 class="modal-title text-center" id="transfer-admin-modal-title">Transfer Admin: <h5 class="text-center" id="transfer-error"></h5></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <div class="btn-group text-center" id="transfer-admin-radio" value="{{ $groups['Group']['id'] }}">
            @foreach ($groups['Group']['users'] as $users)
              @if ($users != $groups['Group']['admin'])
                <div class="radio">
                  <label><input type="radio" name="trainer" value="{{ $users }}">{{ $users }}</label>
                </div>
              @endif
            @endforeach

            @if (count($groups['Group']['users']) == 1)
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


  <div class="modal fade" id="group-invite-modal" tabindex="-1" role="dialog" aria-labelledby="group-invite" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-center" id="group-invite-modal-title">Invite To Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <label>Trainer Name: <input type="text" name="trainer" id="trainer-name-input"></label>
        </div>
        <div class="modal-footer">
					<div class="col-md-6">
          	<button type="button" class="btn btn-primary" id="group-invite-request">Send Request</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="group-disband-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="group-disband" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Are you sure you want to disband the group?:</h5>
        </div>
        <div class="modal-body">
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm-disband-group">Yes</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					</div>
        </div>
      </div>
    </div>
  </div>

	<div class="modal fade" id="group-queue-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="group-queue" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Are you sure you want to re-queue the group?:</h5>
        </div>
        <div class="modal-body">
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm-queue-group">Yes</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					</div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="group-finalize-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="group-finalize" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Are you sure you want to finalize the group and leave the group queue?:</h5>
        </div>
        <div class="modal-body">
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm-finalize-group">Yes</button>
					</div>
					<div class="col-md-6">
          	<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
					</div>
        </div>
      </div>
    </div>
  </div>

@elseif (in_array($groups['User'], $groups['Group']['users']))

@else

@endif

@endsection

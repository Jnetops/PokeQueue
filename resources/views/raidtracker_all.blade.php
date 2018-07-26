@extends('layouts.app')

<link href="{{ asset('css/raidtracker_all.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/raid-tracker.js') }}"></script>

@section('head')
  @if (!empty($raids['Filter']))
  	<meta property="og:url"           content="{{Request::url()}}">
    <meta property="og:type"          content="website">
  	<meta property="fb:app_id"        content="na">
  	<meta property="og:image:width"   content="200">
  	<meta property="og:image:height"   content="200">
    @foreach ($raids['raids'] as $raid)
      <meta property="og:image"         content="{{ URL::to('/') }}/images/sprites/{{$raid->pokemon_id}}.jpg">
      <meta property="og:title"         content="{{$raid->pokemon_name}} {{$raid->star_level}} Star Raid">
      <meta property="og:description"   content="Address: {{$raid->address}} {{$raid->location}}">
    @endforeach
  @endif
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
          <h2 class="text-center">Raid Tracker</h2>
            @if (empty($raids['Filter']))
              <div class="row raid-filter">
                <div class="col-md-3 text-center">
                  <label for="raid-tier">Raid Tiers</label>
                  <select id="raid-tier">
                    <option selected="selected" value="null">Choose A Raid Tier</option>
                    <option value="5">5 Star</option>
                    <option value="4">4 Star</option>
                    <option value="3">3 Star</option>
                    <option value="2">2 Star</option>
                    <option value="1">1 Star</option>
                  </select>
                </div>
                <div class="col-md-3 text-center">
                  <label for="pokemon-id">Pokemon Tiers</label>
                  <select id="pokemon-id">
                    <option selected="selected" value="null">Choose A Pokemon</option>
                  @foreach ($raidPokemon['Raids']["Tier"] as $raid => $raidValue)
          				  @if ($raid == "1")
            					<option disabled>1 Star</option>
                      @foreach ($raidValue as $subRaid)
                        @if ($subRaid["Active"] == "True")
                          <option data-level="1" value={{$subRaid["Number"]}}>{{$subRaid["Name"]}}</option>
                        @endif
                      @endforeach
          				  @elseif ($raid == "2")
          					  <option disabled>2 Star</option>
                      @foreach ($raidValue as $subRaid)
                        @if ($subRaid["Active"] == "True")
                          <option data-level="2" value={{$subRaid["Number"]}}>{{$subRaid["Name"]}}</option>
                        @endif
                      @endforeach
          				  @elseif ($raid == "3")
            					<option disabled>3 Star</option>
                      @foreach ($raidValue as $subRaid)
                        @if ($subRaid["Active"] == "True")
                          <option data-level="3" value={{$subRaid["Number"]}}>{{$subRaid["Name"]}}</option>
                        @endif
                      @endforeach
          				  @elseif ($raid == "4")
            					<option disabled>4 Star</option>
                      @foreach ($raidValue as $subRaid)
                        @if ($subRaid["Active"] == "True")
                          <option data-level="4" value={{$subRaid["Number"]}}>{{$subRaid["Name"]}}</option>
                        @endif
                      @endforeach
          				  @elseif ($raid == "5")
            					<option disabled>5 Star</option>
            					@foreach ($raidValue as $subRaid)
                        @if ($subRaid["Active"] == "True")
            						  <option data-level="5" value={{$subRaid["Number"]}}>{{$subRaid["Name"]}}</option>
                        @endif
                      @endforeach
                    @endif
                  @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="text-center" for="raid-distance">Raid Distance</label>
                  <select id="raid-distance">
                    <option value="10">10 Miles</option>
                    <option value="25" selected="selected">25 Miles</option>
                    <option value="50">50 Miles</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-default" id="raid-filter-button"><h5><b>Filter Raids</b></h5></button>
                </div>
              </div>
            @else
              <div class="col-md-12">
                <div class="col-md-12">
                  <a class="btn btn-default" id="all-raids" href="/raid/tracker?distance=25">View All Raids</a>
                </div>
              </div>
            @endif
            <div class="row raidtracker">
              <div class="col-md-12" id="raid-tracker-col">
                @if (!$raids['raids']->isEmpty())
                  @foreach ($raids['raids'] as $raid)
                  @if (!empty($raids['Filter']))
                  <div class="col-md-12 raidtracker">
                  @else
                  <div class="col-md-6 raidtracker">
                  @endif
                    <div class="panel raid-panel">
                      <div class="panel-heading raid-heading">
                        <h4 class="text-center">{{$raid->pokemon_name}} - {{$raid->star_level}} Star</h4>
                        @if (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%H') == '00')
                          <h5 class="text-center">Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%i min') }}</h3>
                        @elseif (\Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%H') == '01')
                          <h5 class="text-center">Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%h hour %i min') }}</h3>
                        @else
                          <h5 class="text-center">Expires In: {{ \Carbon\Carbon::now()->diff(\Carbon\Carbon::parse($raid->raid_expire))->format('%h hours %i min') }}</h3>
                        @endif
                      </div>
                      <div class="panel-body raid-body" id="panel-{{$raid->id}}">
                        <div class="row">
                          <div class="col-md-6 text-center">
                            <img src="{{ URL::to('/') }}/images/sprites/{{$raid->pokemon_id}}.png" alt="Image" id="raid-tracker-image"/>
                          </div>
                          <div class="col-md-6 text-center">
                            <h5 class="text-center">{{$raid->address}}</h5>
                            <h5 class="text-center">{{$raid->location}}</h5>
                            <h5 class="text-center">Latitude: {{$raid->gym_lat}}</h5>
                            <h5 class="text-center">Longitude: {{$raid->gym_lon}}</h5>
                          </div>
                          @if (!empty($raids['Filter']))
                            <div class="col-md-12 iframe-col text-center">
                              <iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button&size=large&mobile_iframe=true&appId=na&width=72&height=28" width="72" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                            </div>
                          @endif
                        </div>
                      </div>
                      <div class="panel-footer raid-footer">
                        @if (!empty($raids['Filter']))
                          @if (!Auth::guest())
                            <button class="btn btn-default create-raid-group" data-id="{{ $raid->id }}"><h5 id="h5-create"><b>Create Raid Group</b></h5></button>
                          @else
                            <a class="btn btn-default" id="login-button" href="/login">Login To Create Group</a>
                          @endif
                        @else
                          <div class="col-md-12">
                            <a class="btn btn-default" id="specific-raid" href="{{Request::url()}}/{{$raid->id}}">Group For/Share Raid</a>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                @else
                  <div class="col-md-12 error">
                    <h3 class="text-center">No active raids in your area</h3>
                    <button id="create-raid"><h5><b>Create New Raid</b></h5></button>
                  </div>
                @endif
              </div>
            </div>
            @if (empty($raids['Filter']))
              @if (!$raids['raids']->isEmpty())
                <div class="row nav">
                  <div class="col-md-12 text-center">
                    @if ($raids['raids']->previousPageUrl() != null)
                    <div class="col-md-6 nav-col">
                      <button class="btn btn-default prev" id="nav-button" data-url="{{$raids['raids']->previousPageUrl()}}">Prev</button>
                    </div>
                    @else
                    <div class="col-md-6 nav-col">
                      <button disabled="disabled" class="btn btn-default prev" id="nav-button" data-url="{{$raids['raids']->previousPageUrl()}}">Prev</button>
                    </div>
                    @endif
                    @if ($raids['raids']->nextPageUrl() != null)
                    <div class="col-md-6 nav-col">
                      <button class="btn btn-default next" id="nav-button" data-url="{{$raids['raids']->nextPageUrl()}}">Next</button>
                    </div>
                    @else
                    <div class="col-md-6 nav-col">
                      <button disabled="disabled" class="btn btn-default next" id="nav-button" data-url="{{$raids['raids']->nextPageUrl()}}">Next</button>
                    </div>
                    @endif
                  </div>
                </div>
              @endif
            @endif
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

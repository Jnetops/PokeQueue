@extends('layouts.app')


<link href="{{ asset('css/raidtracker_form.css') }}" rel="stylesheet">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/raid-tracker.js') }}"></script>

@section('content')

<div class="container">
  @if(session()->has('error'))
    <div class="row alert alert-warning text-center">
        <h4 class="error-message">{{session('error')}}</h4>
    </div>
  @endif
  <div class="row">
    <div class="panel">
      <form class="form-horizontal" role="form" method="POST" action="/raid/tracker/add">
        {{ csrf_field() }}
        <div class="panel panel-heading raid text-center">
          <h1>Add A Raid</h1>
        </div>
        <div class="panel panel-body raid">

          <div class="col-md-12 text-center">
            @if ($errors->has('pokemon_id'))
                <span class="help-block">
                    <strong>* {{ $errors->first('pokemon_id') }}</strong>
                </span>
            @endif
            <div class="col-md-12 text-center">
              <label for="pokemon_id">Pokemon:</label>
              <select id="pokemon_id" name="pokemon_id">
                <option selected="selected" value="null">Choose A Pokemon</option>
              @foreach ($raids['Raids']["Tier"] as $raid => $raidValue)
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
              <input id="star_level" type="hidden" name="star_level" value="">
            </div>
          </div>

          <div class="col-md-12 text-center">

            @if ($errors->has('raid_expire_hour'))
                <span class="help-block">
                    <strong>* {{ $errors->first('raid_expire_hour') }}</strong>
                </span>
            @endif
            @if ($errors->has('raid_expire_minute'))
                <span class="help-block">
                    <strong>* {{ $errors->first('raid_expire_minute') }}</strong>
                </span>
            @endif
            <div class="col-md-6 text-center">
              <label class="expire-label" for="raid_expire_hour">Expiration Hour:</label>

              <input type="text" id="raid_expire_hour" name="raid_expire_hour" placeholder="Expiration Hour">
            </div>
            <div class="col-md-6 text-center">
              <label class="expire-label" for="raid_expire_minute">Expiration Minute(s):</label>

              <input type="text" id="raid_expire_minute" name="raid_expire_minute" placeholder="Expiration Minute(s)">
            </div>

          </div>

          <div class="col-md-12 text-center">

            @if ($errors->has('address'))
                <span class="help-block">
                    <strong>* {{ $errors->first('address') }}</strong>
                </span>
            @endif
            <div class="col-md-12 text-center">
              <label for="address" class="address">Address:</label><br>

              <input type="text" class="text-center" id="address" name="address" placeholder="Enter Street Address">
            </div>

          </div>

          <div class="col-md-12 text-center">

            @if ($errors->has('gym_city'))
                <span class="help-block">
                    <strong>* {{ $errors->first('gym_city') }}</strong>
                </span>
            @endif
            @if ($errors->has('gym_state'))
                <span class="help-block">
                    <strong>* {{ $errors->first('gym_state') }}</strong>
                </span>
            @endif
            <div class="col-md-6 text-center">
              <label for="city" class="city">City:</label>
              <input type="text" class="text-center" id="city" name="gym_city" placeholder="Enter City">
            </div>
            <div class="col-md-6 text-center">
              <label for="state" class="state">State:</label>
              <select class="form-control text-center" id="state" name="gym_state">
                <option class="text-center" selected="selected">Select A State</option>
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

          </div>

          <div class="col-md-12 text-center">
            @if ($errors->has('gym_lat'))
                <span class="help-block">
                    <strong>* {{ $errors->first('gym_lat') }}</strong>
                </span>
            @endif
            @if ($errors->has('gym_lon'))
                <span class="help-block">
                    <strong>* {{ $errors->first('gym_lon') }}</strong>
                </span>
            @endif
            <div class="col-md-6">
              <label for="gym_lon">Gym Latitude:</label>
              <input type="text" id="gym_lat" name="gym_lat" placeholder="Gym Latitude *Optional*">
            </div>
            <div class="col-md-6">
              <label for="gym_lon">Gym Longitude:</label>
              <input type="text" id="gym_lon" name="gym_lon" placeholder="Gym Longitude *Optional*">
            </div>
          </div>


        </div>
        <div class="panel panel-footer raid">
          <div class="col-md-12 text-center">

            <div class="col-md-12">
                <button type="submit" class="btn btn-default raidtracker-add">
                  Submit Raid
                </button>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

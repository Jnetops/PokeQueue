@extends('layouts.app')

<link href="{{ asset('css/poketracker_all.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/poke-tracker.js') }}"></script>

@section('head')
  @if (!empty($pokemons['Filter']))
  	<meta property="og:url"           content="{{Request::url()}}">
    <meta property="og:type"          content="website">
  	<meta property="fb:app_id"        content="na">
  	<meta property="og:image:width"   content="200">
  	<meta property="og:image:height"   content="200">
    @foreach ($pokemons['Pokemons'] as $pokemon)
      <meta property="og:image"         content="{{ URL::to('/') }}/images/sprites/{{$pokemon->pokemon_id}}.jpg">
      <meta property="og:title"         content="{{$pokemon->pokemon_name}} ({{ $pokemon->pokemon_rarity }})">
      @if (!empty($pokemon->pokemon_cp))
        @if (!empty($pokemon->pokemon_iv))
          <meta property="og:description"   content="CP: {{$pokemon->pokemon_cp}}  IV: {{$pokemon->pokemon_iv}} Lat: {{$pokemon->pokemon_lat}} Long: {{$pokemon->pokemon_lon}}">
        @else
          <meta property="og:description"   content="CP: {{$pokemon->pokemon_cp}}  IV: Not Known Lat: {{$pokemon->pokemon_lat}} Long: {{$pokemon->pokemon_lon}}">
        @endif
      @else
        @if (!empty($pokemon->pokemon_iv))
          <meta property="og:description"   content="CP: Not Known  IV: {{$pokemon->pokemon_iv}} Lat: {{$pokemon->pokemon_lat}} Long: {{$pokemon->pokemon_lon}}">
        @else
          <meta property="og:description"   content="CP: Not Known  IV: Not Known Lat: {{$pokemon->pokemon_lat}} Long: {{$pokemon->pokemon_lon}}">
        @endif
      @endif
    @endforeach
  @endif
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
          <h2 class="text-center">Poke Tracker</h2>
            @if (empty($pokemons['Filter']))
              <div class="row filter-options">
                <div class="col-md-3">
                  <label for="pokemon-rarity">Pokemon Rarity</label>
                  <select id="pokemon-rarity">
                    <option selected="selected" value="undefined">Choose A Rarity</option>
                    <option value="Common">Common</option>
                    <option value="Uncommon">Uncommon</option>
                    <option value="Rare">Rare</option>
                    <option value="Very Rare">Very Rare</option>
                    <option value="Ultra Rare">Ultra Rare</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="pokemon-iv">Pokemon IV%</label>
                  <select id="pokemon-iv">
                    <option selected="selected" value="undefined">Choose IV%</option>
                    <option value="100">100%</option>
                    <option value="90">>90%</option>
                    <option value="80">>80%</option>
                    <option value="70">>70%</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="pokemon-distance">Pokemon Distance</label>
                  <select id="pokemon-distance">
                    <option value="10">10</option>
                    <option value="25" selected="selected">25</option>
                    <option value="50">50</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-default" id="pokemon-filter-button"><h5><b>Filter Pokemon</b></h5></button>
                </div>
              </div>
            @else
              <div class="col-md-12">
                <a class="btn btn-default" id="all-pokemon" href="/poke/tracker?distance=25">View All Pokemon</a>
              </div>
            @endif
            <div class="row poketracker">
              <div class="col-md-12" id="poke-tracker-col">
                @if (!$pokemons['Pokemons']->isEmpty())
                  @foreach ($pokemons['Pokemons'] as $pokemon)
                  <div class="panel pokemon-panel">
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
                    <div class="panel-body poke-body" id="panel-{{$pokemon->id}}">
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
                        @if (!empty($pokemons['Filter']))
                          <div class="col-md-12 iframe-col text-center">
                            <iframe src="https://www.facebook.com/plugins/share_button.php?href={{Request::url()}}&layout=button&size=large&mobile_iframe=true&appId=na&width=72&height=28" width="72" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                          </div>
                        @else
                          <a class="btn btn-default" id="specific-pokemon" href="{{Request::url()}}/{{$pokemon->id}}">View/Share Pokemon</a>
                        @endif
                      </div>
                    </div>
                  </div>
                  @endforeach
                @else
                  <div class="col-md-12 error">
                    <h3 class="text-center">No active pokemon submitted in your area</h3>
                    <button id="create-pokemon"><h5><b>Submit Pokemon Spotting</b></h5></button>
                  </div>
                @endif
              </div>
            </div>
          @if (empty($pokemons['Filter']))

            @if (!$pokemons['Pokemons']->isEmpty())
              <div class="row nav">
                <div class="col-md-12 text-center">
                  @if ($pokemons['Pokemons']->previousPageUrl() != null)
                  <div class="col-md-6 nav-col">
                    <button class="btn btn-default prev" id="nav-button" data-url="{{$pokemons['Pokemons']->previousPageUrl()}}">Prev</button>
                  </div>
                  @else
                  <div class="col-md-6 nav-col">
                    <button disabled="disabled" class="btn btn-default prev" id="nav-button" data-url="{{$pokemons['Pokemons']->previousPageUrl()}}">Prev</button>
                  </div>
                  @endif
                  @if ($pokemons['Pokemons']->nextPageUrl() != null)
                  <div class="col-md-6 nav-col">
                    <button class="btn btn-default next" id="nav-button" data-url="{{$pokemons['Pokemons']->nextPageUrl()}}">Next</button>
                  </div>
                  @else
                  <div class="col-md-6 nav-col">
                    <button disabled="disabled" class="btn btn-default next" id="nav-button" data-url="{{$pokemons['Pokemons']->nextPageUrl()}}">Next</button>
                  </div>
                  @endif
                </div>
              </div>
            @endif
          @endif
        </div>
    </div>
</div>
@endsection

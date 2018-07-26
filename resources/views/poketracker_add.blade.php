@extends('layouts.app')


<link href="{{ asset('css/poketracker_form.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/poke-tracker.js') }}"></script>


@section('content')

<div class="container">
  @if(session()->has('error'))
    <div class="row alert alert-warning text-center">
        <h4 class="error-message">{{session('error')}}</h4>
    </div>
  @endif
  <div class="row">
    <div class="panel">
      <form class="form-horizontal" role="form" method="POST" action="/poke/tracker/add">
        <div class="panel panel-heading poke text-center">
          <h1>Add A Pokemon</h1>
        </div>
        <div class="panel panel-body poke">
              {{ csrf_field() }}
              <div class="col-md-12 text-center">
                @if ($errors->has('pokemon_id'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_id') }}</strong>
                    </span>
                @endif
                <div class="col-md-12 text-center">
                  <label for="pokemon_id">Pokemon:</label>
                  <select id="pokemon_id" name="pokemon_id">
                    <option selected="selected">Choose A Pokemon</option>
                    @foreach ($pokemon as $key => $value)
                      <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-12 text-center">
                @if ($errors->has('pokemon_lat'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_lat') }}</strong>
                    </span>
                @endif
                @if ($errors->has('pokemon_lon'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_lon') }}</strong>
                    </span>
                @endif
                <div class="col-md-6">
                  <label for="pokemon_lat">Latitude:</label>
                  <input type="text" id="pokemon_lat" name="pokemon_lat" placeholder="Enter Latitude">
                </div>

                <div class="col-md-6">
                  <label for="pokemon_lon">Longitude:</label>
                  <input type="text" id="pokemon_lon" name="pokemon_lon" placeholder="Enter Longitude">
                </div>
              </div>

              <div class="row row-margin">
                <div class="col-md-12 text-center">
                  <h4 class="text-center">Confirmed Stats (Requires Trainer Level 30 Or Higher!)</h4>
                </div>
              </div>

              <div class="col-md-12 text-center">
                @if ($errors->has('expire_hour'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('expire_hour') }}</strong>
                    </span>
                @endif
                @if ($errors->has('expire_minute'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('expire_minute') }}</strong>
                    </span>
                @endif
                <div class="col-md-6">
                  <label for="pokemon_expire_hour">Expiration Hour:</label>

                  <input type="text" id="pokemon_expire_hour" name="expire_hour" placeholder="Expiration Hour">
                </div>

                <div class="col-md-6">
                  <label for="pokemon_expire_minute">Expiration Minute(s):</label>

                  <input type="text" id="pokemon_expire_minute" name="expire_minute" placeholder="Expiration Minute(s)">
                </div>
              </div>

              <div class="col-md-12 text-center">
                @if ($errors->has('pokemon_cp'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_cp') }}</strong>
                    </span>
                @endif
                @if ($errors->has('pokemon_iv'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_iv') }}</strong>
                    </span>
                @endif
                <div class="col-md-6">
                  <label for="pokemon_cp">Pokemon CP:</label>
                  <input type="text" id="pokemon_cp" name="pokemon_cp" placeholder="Enter Pokemon CP">
                </div>
                <div class="col-md-6">
                  <label for="pokemon_iv">Pokemon IV %:</label>
                  <input type="text" id="pokemon_iv" name="pokemon_iv" placeholder="Enter Pokemon IV">
                </div>
              </div>

              <div class="col-md-12 text-center">
                @if ($errors->has('pokemon_move_1'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_move_1') }}</strong>
                    </span>
                @endif
                @if ($errors->has('pokemon_move_2'))
                    <span class="help-block">
                        <strong>* {{ $errors->first('pokemon_move_2') }}</strong>
                    </span>
                @endif
                <div class="col-md-6">
                  <label for="move_set_1">Fast Attack:</label>

                  <select class="form-control" id="fast_move" name="pokemon_move_1">
                    <option selected="selected">Choose Pokemon First</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="move_set_1">Charge Attack:</label>

                  <select class="form-control" id="charge_move" name="pokemon_move_2">
                    <option selected="selected">Choose Pokemon First</option>
                  </select>
                </div>
              </div>

        </div>
        <div class="panel panel-footer poke">
          <div class="col-md-12 text-center">
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-default poketracker-add">
                Add Pokemon
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

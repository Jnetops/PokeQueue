@extends('layouts.app')


<link href="{{ asset('css/group-create.css') }}" rel="stylesheet">

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/group.js') }}"></script>

@section('content')
<input type="hidden" value="create-group" id="page-type">
<div class="container">
  @if(session()->has('error'))
    <div class="row alert alert-warning text-center">
        <h4 class="error-message">{{session('error')}}</h4>
    </div>
  @endif
  <div class="row">
    <div class="panel">
      <form action="create" method="POST" role="form" class="form-horizontal" id="queue-form">
        {{ csrf_field() }}
      <div class="panel panel-heading group text-center">
        <h1>Create Group</h1>
      </div>
      
      <div class="panel panel-body group">
      
        <div class="col-md-12 text-center">
          <div class="col-md-12">
            <label for="group-count" class="group-count">Number Of Members:</label>
            <select class="form-control" id="group-count" name="count">
              <option selected="selected"></option>
              <option value="1">5</option>
              <option value="2">10</option>
              <option value="3">15</option>
              <option value="4">20</option>
              <option value="5">25</option>
              <option value="6">30</option>
            </select>
          </div>
        </div>
      
        <div class="col-md-12 text-center">
          <div class="col-md-4">
            <label for="type" class="type">Group Type:</label>
            <select class="form-control type" id="type" name="type">
              <option selected="selected"></option>
              <option value="1">Gym Battles</option>
              <option value="2">Pokemon Farming</option>
              <option value="3">Item Farming</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="teams" class="teams">Team:</label>
            <select class="form-control teams" id="teams" name="subType1">
              <option selected="selected"></option>
              <option value="1">Valor</option>
              <option value="2">Mystic</option>
              <option value="3">Instinct</option>
            </select>

            <label for="pokemon-farming" class="pokemon-farming">Farm Type:</label>
            <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
              <option selected="selected"></option>
              <option value="1">Event Farming</option>
              <option value="2">Nest Farming</option>
              <option value="3">All Farming</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="nest-farming" class="nest-farming">Nest Type:</label>
            <select class="form-control nest-farming" id="nest-farming" name="subType2">
              <option selected="selected">Choose A Pokemon</option>
              @foreach ($pokemon as $key => $value)
              <option value="{{$value}}">{{$key}}</option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div class="col-md-12 text-center">
          <div class="col-md-12 text-center">
            <label for="address" class="address">Address:</label><br>
            <input type="text" id="address" name="address" placeholder="Enter Address">
          </div>
        </div>
        
        <div class="col-md-12 text-center">
          <div class="col-md-6 text-center">
            <label for="city" class="city">City:</label>
            <input type="text" id="city" name="city" placeholder="Enter City">
          </div>
          <div class="col-md-6 text-center">
            <label for="state" class="state">State:</label>
            <select class="form-control" id="state" name="state">
              <option selected="selected">Select A State</option>
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
          <div class="col-md-12 text-center">
            <label for="description" class="description">Description:</label>
            <textarea class="form-control" rows="3" maxlength="255" id="description" name="description"></textarea>
          </div>
        </div>
        
      </div>
      
      <div class="panel panel-footer group">
      
        <div class="col-md-12 text-center">
            <button type="button" class="btn btn-default group-queue" id="queue">
              Queue
            </button>
        </div>
        
      </div>
      </form>
      
    </div>
  </div>
</div>

@endsection

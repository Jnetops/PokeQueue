@extends('layouts.app')

<link href="{{ asset('css/event-create.css') }}" rel="stylesheet">
<link href="{{ asset('datetime/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
<link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" media="screen">



@section('content')
<input type="hidden" value="create-event" id="page-type">
<div class="container">
  @if(session()->has('error'))
    <div class="row alert alert-warning text-center">
        <h4 class="error-message">{{session('error')}}</h4>
    </div>
  @endif
  <div class="row">

    <div class="panel">
      <form action="create" method="POST" role="form" class="form-horizontal" id="event-form">
        {{ csrf_field() }}
      <div class="panel panel-heading event text-center">
        <h1>Create Event</h1>
      </div>
      <div class="panel panel-body event">

        <div class="col-md-12 text-center">
          <div class="col-md-12">
            <label for="group-count" class="group-count">Number Of Members:</label>
            <select class="form-control" id="group-count" name="count">
              <option selected="selected">Group Count</option>
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
              <label for="type" class="type">Event Type:</label>
              <select class="form-control type" id="type" name="type">
                <option selected="selected">Event Type</option>
                <option value="1">Gym Battles</option>
                <option value="2">Pokemon Farming</option>
                <option value="3">Item Farming</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="teams" class="teams">Team:</label>
              <select class="form-control teams" id="teams" name="subType1">
                <option selected="selected">Team Select</option>
                <option value="1">Valor</option>
                <option value="2">Mystic</option>
                <option value="3">Instinct</option>
              </select>

              <label for="pokemon-farming" class="pokemon-farming">Farm Type:</label>
              <select class="form-control pokemon-farming" id="pokemon-farming" name="subType1">
                <option selected="selected">Farm Type</option>
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
              <option selected="selected">Choose State</option>
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
                <form action="" class="form-horizontal"  role="form">
                    <fieldset>
                        <div class="form-group time-entry">
                            <div class="col-md-3"><h4>Select Date/Time</h4></div>
                            <div class="input-group date form_datetime col-md-9 text-center" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                                <input class="form-control" size="16" type="text" value="" readonly>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                            </div>
                    <input type="hidden" id="dtp_input1" value="" /><br/>
                        </div>
                    </fieldset>
                </form>
          </div>

          <input type="hidden" value="" id="month" name="month">
          <input type="hidden" value="" id="year" name="year">
          <input type="hidden" value="" id="day" name="day">
          <input type="hidden" value="" id="time" name="time"></input>
        </div>

        <div class="col-md-12 text-center">

          <div class="col-md-12">
            <label for="description" class="description">Description:</label>
            <textarea class="form-control" rows="3" maxlength="255" id="description" name="description"></textarea>
          </div>

        </div>

      </div>
      <div class="panel panel-footer event">
        <div class="col-md-12">
          <div class="col-md-12 text-center">
            <button class="btn btn-default" id="event-create">Schedule Event</button>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript" src="{{ URL::asset('datetime/jquery/jquery-1.8.3.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ URL::asset('js/event.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('datetime/bootstrap/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script type="text/javascript" src="{{ URL::asset('js/moment.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
var j = jQuery.noConflict();
j( function() {
  j('.form_datetime').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startDate: new Date(),
		startView: 2,
		forceParse: 0,
        showMeridian: 1
      });
});

$(document).ready(function() {
  function TypeCheck() {
    var types = [1,2,3];
    var subTypes1 = [1,2,3];
    if ($("#type").val() - 1 in types) {
      if ($("#type").val() == "1") {
        if ($("#teams").val() - 1 in subTypes1) {
          $("#pokemon-farming").remove();
          $("#nest-farming").remove();
          return true;
        }
        else {
          $(".type").text("Team: Invalid Team Selection");
          //$('<h5 class="error-display">Invalid Team Selection</h5>').insertBefore("#teams");
          return false;
        }
      }
      else if ($("#type").val() == "2") {
        if ($("#pokemon-farming").val() - 1 in subTypes1) {
          if ($("#pokemon-farming").val() == "1" || $("#pokemon-farming").val() == "3") {
            $("#nest-farming").remove();
            $("#teams").remove();
            return true;
          }
          else {
            if ($("#nest-farming").val() <= 251 && $("#nest-farming").val() >= "1") {
              $("#teams").remove();
              return true;
            }
            else {
              $(".nest-farming").text("Nest Type: Invalid Nest Selection");
              //$('<h5 class="error-display">Invalid Nest Selection</h5>').insertBefore("#nest-farming");
              return false;
            }
          }
        }
        else {
          $(".pokemon-farming").text("Farming Type: Invalid Farm Selection");
          //$('<h5 class="error-display">Invalid Farming Selection</h5>').insertBefore("#pokemon-farming");
          return false;
        }
      }
      else {
        $("#teams").remove();
        $("#pokemon-farming").remove();
        $("#nest-farming").remove();
        return true;
      }
    }
    else {
      $(".type").text("Event Type: Invalid Type Selection");
      //$('<h5 class="error-display">Invalid Queue Type</h5>').insertBefore("#type");
      return false;
    }
  }

  function hideShowTypes () {
    if ($("#type").val() == "1") {
      $(".pokemon-farming").hide();
      $(".nest-farming").hide();
      $(".teams").show();
    }
    else if ($("#type").val() == "2") {
      if ($("#pokemon-farming").val() == "2") {
        $(".pokemon-farming").show();
        $(".nest-farming").show();
        $(".teams").hide();
      }
      else {
        $(".pokemon-farming").show();
        $(".nest-farming").hide();
        $(".teams").hide();
      }
    }
    else if ($("#type").val() == "3") {
      $(".pokemon-farming").hide();
      $(".nest-farming").hide();
      $(".teams").hide();
    }
    else if ($("#type").val() == "") {
      $(".pokemon-farming").hide();
      $(".nest-farming").hide();
      $(".teams").hide();
    }
  }

  $( "#type,#teams,#pokemon-farming,#nest-farming" ).change(function() {
    hideShowTypes();
  });
  
  function PrivacyCheck() {
    if ($("#privacy").val() == "0") {
      $(".privacy-sub").hide();
    }
    else if ($("#privacy").val() == "1") {
      $(".privacy-sub").show();
    }
  }

  $( "#privacy" ).change(function() {
    PrivacyCheck();
  });
  
  
  $("#event-create").click(function() {
    var tCheck = TypeCheck();
    if ($("#privacy-sub").val() == "") {
      if ($("#privacy").val() != "0") {
        return false;
      }
      else {
        $("#privacy-sub").remove();
      }
    }
    if (tCheck == true && $("#city").val() != "" && $('#state').find(":selected").text() != "" && $("#address").val() != "") {
      var timepicker = j(".form_datetime").data("datetimepicker").getDate();
      var datetime = new Date(timepicker);
      var Hours = datetime.getHours();
      var Minutes = datetime.getMinutes();
      var Day = datetime.getDate();
      if (datetime.getMonth() == '11')
      {
        var Month = '12';
      }
      else {
        var Month = parseInt(datetime.getMonth()) + 1;
      }
      var Year = datetime.getFullYear();
      $("#day").val(Day);
      $("#month").val(Month);
      $("#year").val(Year);
      $("#time").val(Hours + ":" + Minutes + ":00");

      if($("#day").val() != "" && $("#month").val() != "" && $("#year").val() != "" && $("#time").val() != "")
      {            
        $('#event-form').submit();
      }
      else {
        alert('Date of Event Required');
      }
    }
    else {
      alert('Invalid Address Information Supplied');
    }
    
  });
});
</script>

@endsection

$(document).ready(function() {

  $("#password-edit").click(function() {

    $("#password").hide();
    $("#password-edit").hide();
    $("#col-password").hide();

    $(".enter-password").show();
    $("#password-submit").show();

    $(".team").show();
    $("#trainer-team-select").hide();
    $("#team-edit").show();
    $("#team-submit").hide();

    $("#trainer-level-display").show();
    $("#trainer-level-select").hide();
    $("#level-edit").show();
    $("#level-submit").hide();

    $("#location-display").show();
    $(".location-inputs").hide();
    $("#location-edit").show();
    $("#location-submit").hide();
  });

  $("#name-edit").click(function() {

    $("#trainer-name-display").hide();
    $("#trainer-name-input").show();
    $("#name-edit").hide();
    $("#name-submit").show();

  });

  $("#team-edit").click(function() {

    $("#trainer-team").attr('id', 'trainer-team-expand');
    $("#trainer-level-expand").attr('id', 'trainer-level');
    $(".team").hide();
    $("#trainer-team-select").show();
    $("#team-edit").hide();
    $("#team-submit").show();

    $("#password").show();
    $("#password-edit").show();
    $("#col-password").show();
    $(".enter-password").hide();
    $("#password-submit").hide();

    $("#trainer-level-display").show();
    $("#trainer-level-select").hide();
    $("#level-edit").show();
    $("#level-submit").hide();

    $("#location-display").show();
    $(".location-inputs").hide();
    $("#location-edit").show();
    $("#location-submit").hide();

  });

  $("#level-edit").click(function() {

    $("#trainer-level").attr('id', 'trainer-level-expand');
    $("#trainer-team-expand").attr('id', 'trainer-team');
    $("#trainer-level-display").hide();
    $("#trainer-level-select").show();
    $("#level-edit").hide();
    $("#level-submit").show();

    $("#password").show();
    $("#password-edit").show();
    $("#col-password").show();
    $(".enter-password").hide();
    $("#password-submit").hide();

    $(".team").show();
    $("#trainer-team-select").hide();
    $("#team-edit").show();
    $("#team-submit").hide();

    $("#location-display").show();
    $(".location-inputs").hide();
    $("#location-edit").show();
    $("#location-submit").hide();

  });

  $("#location-edit").click(function() {

    $("#trainer-team-expand").attr('id', 'trainer-team');
    $("#trainer-level-expand").attr('id', 'trainer-level');

    $("#location-display").hide();
    $(".location-inputs").show();
    $("#location-edit").hide();
    $("#location-submit").show();

    $("#password").show();
    $("#password-edit").show();
    $("#col-password").show();
    $(".enter-password").hide();
    $("#password-submit").hide();

    $(".team").show();
    $("#trainer-team-select").hide();
    $("#team-edit").show();
    $("#team-submit").hide();

    $("#trainer-level-display").show();
    $("#trainer-level-select").hide();
    $("#level-edit").show();
    $("#level-submit").hide();

  });

  $("#password-submit").click(function() {

    if ($("#enter-password-input").val() == $("#re-enter-password-input").val())
    {
      if ($("#enter-password-input").val().length >= 8 ) {
        $.post("profile/update/password", { password:$("#enter-password-input").val(), "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
            if (data['Success'] == 'True') {
              location.reload();
            }
            else {
              alert('Failed to update password');
            }
        });
      }
      else {
        alert('Minimum Password Length Is 8 Characters');
      }
    }
    else {
      alert('Password Mismatch');
    }

  });

  $("#team-submit").click(function() {
    if ($.inArray($("#trainer-team-select").val(), [1,2,3]))
    {
      $.post("profile/update/team", { trainer_team:$("#trainer-team-select").val(), "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
          if (data == true) {
            location.reload();
          }
          else {
            alert('Failed To Update Trainer Team');
          }
      });
    }
    else {
      alert('Invalid Team Selection');
    }
  });

  $("#level-submit").click(function() {
    if ($.inArray($("#trainer-level-select").val(), [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40]))
    {
      $.post("profile/update/level", { trainer_level:$("#trainer-level-select").val(), "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
          if (data == true) {
            location.reload();
          }
          else {
            alert('Failed To Update Trainer Level');
          }
      });
    }
    else {
      alert('Invalid Level Selection');
    }
  });

  $("#location-submit").click(function() {
    if ($.inArray($("#location-state-input").val(), ["AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"]))
    {
      if ($("#location-city-input").val() != "")
      {
        $.post("profile/update/location", { city:$("#location-city-input").val(), state:$("#location-state-input").val(), "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
            if (data == true) {
              location.reload();
            }
            else {
              alert('Failed To Update Trainer Level');
            }
        });
      }
      else {
        alert('City Cannot Be Empty');
      }

    }
    else {
      alert('Invalid State Selection');
    }
  });

  $("#gps-button-profile").click(function() {
    var geolocation = navigator.geolocation;
    if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(success_handler, error_handler);
    }
    function success_handler(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      $.post("profile/update/location", { lat:latitude, lon:longitude, "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
          if (data == true) {
            location.reload();
          }
          else {
            alert('Failed To Update Trainer Level');
          }
      });

    }
    function error_handler(error) {
      alert('Unable To Update Location');
    }
  });

  $("#remove-friends").click(function() {
    var checkedFriends = '{"friends":[';
    $('input[type="checkbox"]:checked').each(function() {
      if (checkedFriends == '{"friends":[')
      {
        checkedFriends = checkedFriends + '"' + this.value + '"';
      }
      else {
        checkedFriends = checkedFriends + ',"' + this.value + '"';
      }
    });
    checkedFriends = checkedFriends + "]}";

    $.ajaxSetup(
    {
        headers:
        {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var request = $.ajax({
      url: "/profile/friends/remove",
      type: "POST",
      data: checkedFriends,
      dataType: "json",
      contentType: "application/json",
      success: function( data ) {
        location.reload();
      }
    });

  });

  $(".view-group").click(function() {
    window.location.href = "/group/" + $(this).attr("data-id");
  });
  $(".view-event").click(function() {
    window.location.href = "/events/" + $(this).attr("data-id");
  });

  $("#create-raid").click( function() {
    window.location.href = "/raid/tracker/add"
  });

  $("#create-pokemon").click( function() {
    window.location.href = "/poke/tracker/add"
  });

  $("#find-event").click( function() {
    window.location.href = "/events?distance=25"
  });

  $("#find-group").click( function() {
    window.location.href = "/groups?distance=25"
  });

  $(".add-friend").click(function() {
    $.get( "/friends/add?trainer=" + $("#trainer-name-display").text(), function( data ) {
	  	if (data['Success'] == 'True')
  		{
  			location.reload();
  		}
  		else {
  			alert(data['Error']);
  		}
	 });
 });


});

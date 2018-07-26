$(document).ready(function() {

$("#notifications-ul").click(function(e) {
  e.stopPropagation();
});

function emptyNotification()
{
  if ($("#notifications-ul li").length == 0)
  {
    $("#notifications-ul").append('<li class="notification-li"><div class="notification-item text-center"><h3 id="no-notifications">No New Noticiations</h3></div></li>');
  }
}

$(".mark-read.btn").click(function() {
  var id = $(this).attr('id');
  $.ajax({url: "/notification/" + id, success: function(result){
    if (result['Success'] == "True")
    {
      $("#" + id).parent().parent().parent().remove();
      emptyNotification();
    }
    else {
      alert('Unable to mark as read');
    }
  }});
});

$("#accept-event-invite").click(function() {
  var id = $(this).attr("data-id");
  var notificationID = $(this).closest('.notification-item').find('.mark-read').attr('id');
  if ($(this).attr("data-user"))
  {
    var trainer = $(this).attr("data-user");
    $.ajax({url: "/events/request/accept", type: "GET", data: {'event-id':id, 'trainer':trainer}, success: function(result){

    if (result['Success'] == 'True')
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
    }});

  }
  else {
    $.ajax({url: "/events/invite/accept", type: "GET", data: {'event-id':id}, success: function(result){
    if (result['Success'] == 'True')
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
    }});
  }
});

$("#reject-event-invite").click(function() {
  var id = $(this).attr("data-id");
  var notificationID = $(this).closest('.notification-item').find('.mark-read').attr('id');
  if ($(this).attr("data-user"))
  {
    var trainer = $(this).attr("data-user");
    $.ajax({url: "/events/reject/request", type: "GET", data: {'event-id':id, 'trainer':trainer}, success: function(result){

    if (result['Success'] == 'True')
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
    }});

  }
  else {
    $.ajax({url: "/events/reject/invite", type: "GET", data: {'event-id':id}, success: function(result){
    if (result['Success'] == 'True')
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
    }});
  }
});

$("#accept-friend-request").click(function() {
  var notificationID = $(this).closest('.notification-item').find('.mark-read').attr('id');
  $.ajax({url: "/friends/accept", type: "GET", data: {'trainer':$(this).attr("data-user")}, success: function(result){
    if (result['Success'] == "True")
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
  }});
});

$("#reject-friend-request").click(function() {
  var notificationID = $(this).closest('.notification-item').find('.mark-read').attr('id');
  $.ajax({url: "/friends/reject", type: "GET", data: {'trainer':$(this).attr("data-user")}, success: function(result){
    if (result['Success'] == "True")
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
  }});
});

$("#accept-group-invite").click(function() {
  var id = $(this).attr("data-id");
  var notificationID = $(this).closest('.notification-item').find('.mark-read').attr('id');
  if ($(this).attr("data-user"))
  {
    var trainer = $(this).attr("data-user");
    $.ajax({url: "/group/request/accept", type: "GET", data: {'group-id':id, 'trainer':trainer}, success: function(result){
    if (result['Error'] == "User already in group")
    {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else if (result['Success'] == 'True') {
      $.ajax({url: "/notification/" + notificationID, success: function(result){
        if (result['Success'] == 'True')
        {
          $('#' + notificationID).parent().parent().remove();
          emptyNotification();
        }
      }});
    }
    else {
      alert(result['Error']);
    }
    }});

  }
  else {
    $.ajax({url: "/group/invite/accept", type: "GET", data: {'group-id':id}, success: function(result){
      if (result['Success'] == 'True')
      {
        $.ajax({url: "/notification/" + notificationID, success: function(result){
          if (result['Success'] == 'True')
          {
            $('#' + notificationID).parent().parent().remove();
            emptyNotification();
          }
        }});
      }
      else {
        alert(result['Error']);
      }
    }});
  }
});

$("#reject-group-invite").click(function() {
  var id = $(this).attr("data-id");
  var notificationID = $(this).closest('.notification-item').find('.mark-read').attr('id');
  if ($(this).attr("data-user"))
  {
    var trainer = $(this).attr("data-user");
    $.ajax({url: "/group/reject/request", type: "GET", data: {'group-id':id, 'trainer':trainer}, success: function(result){
      if (result['Success'] == 'True') {
        $.ajax({url: "/notification/" + notificationID, success: function(result){
          if (result['Success'] == 'True')
          {
            $('#' + notificationID).parent().parent().parent().remove();
            emptyNotification();
          }
        }});
      }
      else {
        alert(result['Error']);
      }
    }});

  }
  else {
    $.ajax({url: "/group/reject/invite", type: "GET", data: {'group-id':id}, success: function(result){
      if (result['Success'] == 'True')
      {
        $.ajax({url: "/notification/" + notificationID, success: function(result){
          if (result['Success'] == 'True')
          {
            $('#' + notificationID).parent().parent().parent().remove();
            emptyNotification();
          }
        }});
      }
      else {
        alert(result['Error']);
      }
    }});
  }
});

  $(".group.btn").click(function() {
    window.location.href = "/group/" + $(this).attr('id');
  });

  $(".event.btn").click(function() {
    window.location.href = "/events/" + $(this).attr('id');
  });

  $(".friend.btn").click(function() {
    window.location.href = "/users/" + $(this).attr('id');
  });

  $(".poketracker.btn").click(function() {
    window.location.href = "/poke/tracker/" + $(this).attr('id');
  });

  $(".raidtracker.btn").click(function() {
    window.location.href = "/raid/tracker/" + $(this).attr('id');
  });


  $("#gps-button").click(function() {
    var geolocation = navigator.geolocation;
    if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(success_handler, error_handler);
    }
    function success_handler(position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;
      $.post("/profile/update/location", { lat:latitude, lon:longitude, "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
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

  $("#submit-location").click(function() {
    if ($.inArray($("#location-input-state").val(), ["AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY"]))
    {
      if ($("#location-input-city").val() != "")
      {
        $.post("/profile/update/location", { address:$("#location-input-address").val(), city:$("#location-input-city").val(), state:$("#location-input-state").val(), "_token": $('meta[name="csrf-token"]').attr('content') }, function(data) {
            if (data == true) {
              location.reload();
            }
            else {
              alert('Failed To Update Trainer Location');
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

});

$(document).ready(function() {
	var eventID = $("#event-id").val();
  var channelSub = pusher.subscribe('event-channel-' + eventID);

  channelSub.bind('App\\Events\\transferAdmin', function(data) {
    $("#user-list").empty();
    $("#transfer-admin-radio");
	$.each(data.users.Users, function( index, value ) {
      if (value.trainer_name == data.users.Admin)
      {
		if (value.trainer_team == 1)
		{
			$("#user-list").append('<li class="user-li admin"><div class="col-md-12 admin-title text-center"><h4>Administrator</h4></div><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/mystic.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
		}
		else if (value.trainer_team == 2)
		{
			$("#user-list").append('<li class="user-li admin"><div class="col-md-12 admin-title text-center"><h4>Administrator</h4></div><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/valor.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
		}
		else {
			$("#user-list").append('<li class="user-li admin"><div class="col-md-12 admin-title text-center"><h4>Administrator</h4></div><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/instinct.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
		}
      }
    });
    $.each(data.users.Users, function( index, value ) {
      if (value.trainer_team == 1 && value.trainer_name != data.users.Admin)
      {
        $("#user-list").append('<li class="user-li"><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/mystic.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
        $("#transfer-admin-radio").append('<div class="radio"><label><input type="radio" name="trainer" value=' +value.trainer_name+ '>' +value+ '</label></div>');
      }
    });
	$.each(data.users.Users, function( index, value ) {
      if (value.trainer_team == 2 && value.trainer_name != data.users.Admin)
      {
        $("#user-list").append('<li class="user-li"><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/valor.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
        $("#transfer-admin-radio").append('<div class="radio"><label><input type="radio" name="trainer" value=' +value.trainer_name+ '>' +value+ '</label></div>');
      }
    });
	$.each(data.users.Users, function( index, value ) {
      if (value.trainer_team == 3 && value.trainer_name != data.users.Admin)
      {
        $("#user-list").append('<li class="user-li"><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/instinct.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
        $("#transfer-admin-radio").append('<div class="radio"><label><input type="radio" name="trainer" value=' +value.trainer_name+ '>' +value+ '</label></div>');
      }
    });
  });

  channelSub.bind('App\\Events\\newMember', function(data) {
    $("#user-list").empty();
    $("#transfer-admin-radio");
	$.each(data.users.Users, function( index, value ) {
      if (value.trainer_name == data.users.Admin)
      {
		if (value.trainer_team == 1)
		{
			$("#user-list").append('<li class="user-li admin"><div class="col-md-12 admin-title text-center"><h4>Administrator</h4></div><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/mystic.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
		}
		else if (value.trainer_team == 2)
		{
			$("#user-list").append('<li class="user-li admin"><div class="col-md-12 admin-title text-center"><h4>Administrator</h4></div><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/valor.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
		}
		else {
			$("#user-list").append('<li class="user-li admin"><div class="col-md-12 admin-title text-center"><h4>Administrator</h4></div><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/instinct.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
		}
      }
    });
    $.each(data.users.Users, function( index, value ) {
      if (value.trainer_team == 1 && value.trainer_name != data.users.Admin)
      {
        $("#user-list").append('<li class="user-li"><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/mystic.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
        $("#transfer-admin-radio").append('<div class="radio"><label><input type="radio" name="trainer" value=' +value.trainer_name+ '>' +value+ '</label></div>');
      }
    });
	$.each(data.users.Users, function( index, value ) {
      if (value.trainer_team == 2 && value.trainer_name != data.users.Admin)
      {
        $("#user-list").append('<li class="user-li"><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/valor.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
        $("#transfer-admin-radio").append('<div class="radio"><label><input type="radio" name="trainer" value=' +value.trainer_name+ '>' +value+ '</label></div>');
      }
    });
	$.each(data.users.Users, function( index, value ) {
      if (value.trainer_team == 3 && value.trainer_name != data.users.Admin)
      {
        $("#user-list").append('<li class="user-li"><a href="/users/'+value.trainer_name+'"><div class="col-md-12 team-image" style="background-image: url(' + "'/images/instinct.png'" + '); background-color: 	#000;"><h5 id="trainer-level-display">Level '+value.trainer_level+'</h5><h5 id="trainer-name-display">'+value.trainer_name+'</h5></div></a></li>');
        $("#transfer-admin-radio").append('<div class="radio"><label><input type="radio" name="trainer" value=' +value.trainer_name+ '>' +value+ '</label></div>');
      }
    });
  });

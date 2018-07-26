$(document).ready(function() {
  function scrollSmoothToBottom (id) {
     var div = document.getElementById(id);
     $('#' + id).animate({
        scrollTop: div.scrollHeight - div.clientHeight
     }, 500);
  }
  var appKey = $("#app-key").val();
  var groupID = $("#group-id").val();

  var pusher = new Pusher(appKey, { cluster: 'us2', authEndpoint: '/group/'+groupID+'/chat/auth', auth: { headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}});
  Pusher.log = function(msg) {
  console.log(msg);
  };
  var channelSub = pusher.subscribe('private-group-channel-' + groupID);
  channelSub.bind('App\\Events\\newMessage', function(data) {
    $(".chat-list-group").append('<li class="li-group-item"><div class="col-md-12 chat-col"><div class="col-md-1 text-center"><div class="profile-avatar tiny text-center" style="background-image: url('+data.text.avatar+')"></div></div><div class="col-md-9"><a class="trainer-name" href="/users/"'+data.text.trainer+'>'+data.text.trainer+'</a><p>'+data.text.text+'</p></div><div class="col-md-2 text-right"><p>'+data.text.timestamp+'</p></div></div></li>');
    $("#group-chat-comment").val('');
    scrollSmoothToBottom('chat-panel');
  });

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

  channelSub.bind('App\\Events\\finalizeAll', function(data) {
  location.reload();
  });

  channelSub.bind('App\\Events\\requeueAll', function(data) {
  location.reload();
  });
  
  channelSub.bind('App\\Events\\disbandAll', function(data) {
    alert('Group Has Been Disbanded');
    window.location.href = "/";
  });

});

$(document).ready(function() {
	var appKey = $("#app-key").val();
	var trainer = $("#trainer").val();

	var pusher = new Pusher(appKey, { cluster: 'us2', authEndpoint: '/notifications', auth: { headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }}});
	Pusher.log = function(msg) {
		console.log(msg);
	};
	var channel = pusher.subscribe('private-' + trainer + '-notification-channel');
	channel.bind('App\\Events\\newNotification', function(data) {

	});

});

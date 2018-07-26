$(document).ready(function() {

  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
  };

  if (getUrlParameter('pokemon-id') != undefined)
  {
    $("#pokemon-id option[value="+getUrlParameter('pokemon-id')+"]").prop('selected', true);
    $("#raid-distance option[value="+getUrlParameter('distance')+"]").prop('selected', true);
  }
  else if (getUrlParameter('raid-tier') != undefined)
  {
    $("#raid-tier option[value="+getUrlParameter('raid-tier')+"]").prop('selected', true);
    $("#raid-distance option[value="+getUrlParameter('distance')+"]").prop('selected', true);
  }

  $("#raid-filter-button").click(function() {
        var distance = $("#raid-distance").find(":selected").val();
        var raidPokemon = $("#pokemon-id").find(":selected").val();
        var raidTier = $("#raid-tier").find(":selected").val();

        if (raidPokemon == 'null' && raidTier == 'null') {
          window.location.href = "tracker" + '?distance=' + distance;
        }
        else if (raidTier != 'null')
        {
          window.location.href = "tracker" + '?distance=' + distance + '&raid-tier=' + raidTier;
        }
        else {
          window.location.href = "tracker" + '?distance=' + distance + '&pokemon-id=' + raidPokemon;
        }
  });

  $(".btn.btn-default.next").click(function() {
      var distance = $("#raid-distance").find(":selected").val();
      if (getUrlParameter('pokemon-id') != undefined)
      {
        window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&pokemon-id=' + getUrlParameter('pokemon-id');
      }
      else {
        window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance;
      }
  });

  $(".btn.btn-default.prev").click(function() {
      var distance = $("#raid-distance").find(":selected").val();
      if (getUrlParameter('pokemon-id') != undefined)
      {
        window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&pokemon-id=' + getUrlParameter('pokemon-id');
      }
      else {
        window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance;
      }
  });

  $(".create-raid-group").click(function() {

    $.ajax({url: "/group/raid/create", type: "POST", data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'raid-id':$(".create-raid-group").attr('data-id')}, success: function(result){

      if (result['Success'] == 'False')
      {
          if (result['Exists'] == 'False')
          {
            alert(result["Error"]);
          }
          else {
            $("#view-group").attr("data-id", result['groupID']);
            $("#group-exists-modal").modal('toggle');
          }
      }
      else {
        window.location.href = "/group/" + result['groupID'];
      }
    }});

  });

  $("#ignore-current").click(function() {
    $.ajax({url: "/group/raid/create", type: "POST", data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'raid-id':$(".create-raid-group").attr('data-id'), 'exists':'ignore'}, success: function(result){

      if (result['Response'] == 'Failed')
      {
          if (result['Exists'] == 'false')
          {
            alert(result["Error"]);
          }
          else {
            $("#view-group").attr("data-id", result['groupID']);
            $("#group-exists-modal").modal('toggle');
          }
      }
      else {
        window.location.href = "/group/" + result['groupID'];
      }
    }});
  });

  $("#view-group").click(function() {
    window.location.href = "group/" + $("#view-group").attr("data-id");
  });

  $("#create-raid").click(function () {
    window.location.href = "/raid/tracker/add";
  });

  $('#pokemon_id').on('change', function() {
    $("#star_level").val($('#pokemon_id').find(':selected').data('level'));
  });


});

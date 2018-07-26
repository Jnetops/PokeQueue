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

  if (getUrlParameter('filter-rarity') == undefined && getUrlParameter('filter-iv') == undefined)
  {
    $("#pokemon-distance option[value="+getUrlParameter('distance')+"]").prop('selected', true);
  }
  else if (getUrlParameter('filter-rarity') != undefined && getUrlParameter('filter-iv') == undefined)
  {
    $("#pokemon-distance option[value="+getUrlParameter('distance')+"]").prop('selected', true);
    $("#pokemon-rarity option[value='"+getUrlParameter('filter-rarity')+"']").prop('selected', true);
  }
  else if (getUrlParameter('filter-rarity') == undefined && getUrlParameter('filter-iv') != undefined)
  {
    $("#pokemon-distance option[value="+getUrlParameter('distance')+"]").prop('selected', true);
    $("#pokemon-iv option[value="+getUrlParameter('filter-iv')+"]").prop('selected', true);
  }
  else if (getUrlParameter('filter-rarity') != undefined && getUrlParameter('filter-iv') != undefined)
  {
    $("#pokemon-distance option[value="+getUrlParameter('distance')+"]").prop('selected', true);
    $("#pokemon-rarity option[value='"+getUrlParameter('filter-rarity')+"']").prop('selected', true);
    $("#pokemon-iv option[value="+getUrlParameter('filter-iv')+"]").prop('selected', true);
  }

  $("#pokemon-filter-button").click(function() {
        var distance = $("#pokemon-distance").find(":selected").val();
        var iv = $("#pokemon-iv").find(":selected").val();
        var rarity = $("#pokemon-rarity").find(":selected").val();

        if (iv == 'undefined' && rarity == 'undefined') {
          window.location.href = "/poke/tracker" + '?page=1' + '&distance=' + distance;
        }
        else if (iv == 'undefined' && rarity != 'undefined')
        {
          window.location.href = "/poke/tracker" + '?page=1' + '&distance=' + distance + '&filter-rarity=' + rarity;
        }
        else if (iv != 'undefined' && rarity == 'undefined')
        {
          window.location.href = "/poke/tracker" + '?page=1' + '&distance=' + distance + '&filter-iv=' + iv;
        }
        else {
          window.location.href = "/poke/tracker" + '?page=1' + '&distance=' + distance + '&filter-rarity=' + rarity + '&filter-iv=' + iv;
        }
  });

  $(".btn.btn-default.next").click(function() {
      var distance = $("#pokemon-distance").find(":selected").val();
      if (getUrlParameter('filter-iv') != undefined && getUrlParameter('filter-rarity') != undefined)
      {
        window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&filter-rarity=' + getUrlParameter('filter-rarity') + '&filter-iv=' + getUrlParameter('filter-iv');
      }
      else if (getUrlParameter('filter-rarity') == undefined && getUrlParameter('filter-iv') != undefined)
      {
        window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&filter-iv=' + getUrlParameter('filter-iv');
      }
      else if (getUrlParameter('filter-rarity') != undefined && !getUrlParameter('filter-iv') == undefined) {
        window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&filter-rarity=' + getUrlParameter('filter-rarity');
      }
      else {
        window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance;
      }
  });

  $(".btn.btn-default.prev").click(function() {
      var distance = $("#pokemon-distance").find(":selected").val();
      if (getUrlParameter('filter-iv') != undefined && getUrlParameter('filter-rarity') != undefined)
      {
        window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&filter-rarity=' + getUrlParameter('filter-rarity') + '&filter-iv=' + getUrlParameter('filter-iv');
      }
      else if (getUrlParameter('filter-rarity') == undefined && getUrlParameter('filter-iv') != undefined)
      {
        window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&filter-iv=' + getUrlParameter('filter-iv');
      }
      else if (getUrlParameter('filter-rarity') != undefined && !getUrlParameter('filter-iv') == undefined) {
        window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&filter-rarity=' + getUrlParameter('filter-rarity');
      }
      else {
        window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance;
      }
  });

  $("#create-pokemon").click(function () {
    window.location.href = "/poke/tracker/add";
  });

  $('#pokemon_id').on('change', function() {

    $('#fast_move option[value!="0"]').remove();
    $('#charge_move option[value!="0"]').remove();

    $.get( "/poke/tracker/moveset?pokemon=" + this.value, function( data ) {
      $.each(data, function( key, value ) {
        if (key == 'fast_move')
        {
          $('#fast_move')
          .append(
            $('<option selected="selected">Select Fast Move</option>')
            .attr("value",'')
          );

          $.each(value, function( k, v ) {

            $('#fast_move')
            .append(
              $("<option></option>")
              .attr("value",v)
              .text(v)
            );

          });
        }
        else if (key == 'charge_move')
        {
          $('#charge_move')
          .append(
            $('<option selected="selected">Select Charge Move</option>')
            .attr("value",'')
          );

          $.each(value, function( k, v ) {

            $('#charge_move')
            .append(
              $("<option></option>")
              .attr("value",v)
              .text(v)
            );

          });
        }
      });

    });

  });


});


$(document).ready(function() {

// all events
  if ($("#page-type").val() == "all-events")
  {
      hideShowTypes();
      function hideShowTypes () {
        if ($("#type").val() == "1") {
          $("#pokemon-farming").hide();
          $("#nest-farming").hide();
          $("#teams").show();
        }
        else if ($("#type").val() == "2") {
          if ($("#pokemon-farming").val() == "2") {
            $("#pokemon-farming").show();
            $("#nest-farming").show();
            $("#teams").hide();
          }
          else {
            $("#pokemon-farming").show();
            $("#nest-farming").hide();
            $("#teams").hide();
            $("#pokemon-farming").show();
          }
        }
        else if ($("#type").val() == "3") {
          $("#pokemon-farming").hide();
          $("#nest-farming").hide();
          $("#teams").hide();
        }
        else if ($("#type").val() == "") {
          $("#pokemon-farming").hide();
          $("#nest-farming").hide();
          $("#teams").hide();
        }
      }

      $( "#type,#teams,#pokemon-farming,#nest-farming" ).change(function() {
        hideShowTypes();
      });

      $("#filter").click(function() {
        var contRedir = true;
        var types = [1,2,3];
        if ($("#type").val() == "1") {
          if ($("#teams").val() - 1 in types)
          {
            var typeParams = "?type=" + $("#type").val() + "&subType1=" + $("#teams").val();
          }
          else {
            var typeParams = "?type=" + $("#type").val();
          }
        }
        else if ($("#type").val() == "2") {
          if ($("#pokemon-farming").val() == "2") {
            if ($("#nest-farming").val() <= 251 && $("#nest-farming").val() >= "1") {
              var typeParams = "?type=" + $("#type").val() + "&subType1=" + $("#pokemon-farming").val() + "&subType2=" + $("#nest-farming").val();
            }
            else {
              var typeParams = "?type=" + $("#type").val() + "&subType1=" + $("#pokemon-farming").val();
            }
          }
          else if ($("#pokemon-farming").val() == "1" || $("#pokemon-farming").val() == "3") {
            var typeParams = "?type=" + $("#type").val() + "&subType1=" + $("#pokemon-farming").val();
          }
          else {
            var typeParams = "?type=" + $("#type").val();
          }
        }
        else if ($("#type").val() == "3") {
          var typeParams = "?type=" + $("#type").val();
        }
        else {
          var contRedir = false;
          alert('Please Make Filter Selections First!');
        }

        var distance = $("#distance").find(":selected").val();

        typeParams = typeParams + "&distance=" + distance;
        if (contRedir == true)
        {
          $.get( "/events" + typeParams );
          window.location.href = "/events" + typeParams;
        }
      });

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

      populateDistance();
      function populateDistance() {
        if (getUrlParameter('distance') != undefined)
        {
          $("#distance").html('');
          if (getUrlParameter('distance') == '10')
          {
            $("#distance").append('<option value="10" selected="selected">10 Miles</option><option value="25">25 Miles</option><option value="50">50 Miles</option>');
          }
          else if (getUrlParameter('distance') == '25')
          {
            $("#distance").append('<option value="10">10 Miles</option><option value="25" selected="selected">25 Miles</option><option value="50">50 Miles</option>');
          }
          else {
            $("#distance").append('<option value="10">10 Miles</option><option value="25">25 Miles</option><option value="50" selected="selected">50 Miles</option>');
          }
        }
      }

      $(".btn.btn-default.next").click(function() {
          var distance = $("#distance").find(":selected").val();
          if (getUrlParameter('type') != undefined && getUrlParameter('subType1') == undefined)
          {
            window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&type=' + getUrlParameter('type');
          }
          else if (getUrlParameter('type') != undefined && getUrlParameter('subType1') != undefined) {
            if (getUrlParameter('subType2') != undefined)
            {
              window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&type=' + getUrlParameter('type') + '&subType1=' + getUrlParameter('subType1') + '&subType2=' + getUrlParameter('subType2');
            }
            else {
              window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance + '&type=' + getUrlParameter('type') + '&subType1=' + getUrlParameter('subType1');
            }
          }
          else {
            window.location.href = $(".btn.btn-default.next").attr("data-url") + '&distance=' + distance;
          }
      });

      $(".btn.btn-default.prev").click(function() {
          var distance = $("#distance").find(":selected").val();
          if (getUrlParameter('type') != undefined && getUrlParameter('subType1') == undefined)
          {
            window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&type=' + getUrlParameter('type');
          }
          else if (getUrlParameter('type') != undefined && getUrlParameter('subType1') != undefined) {
            if (getUrlParameter('subType2') != undefined)
            {
              window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&type=' + getUrlParameter('type') + '&subType1=' + getUrlParameter('subType1') + '&subType2=' + getUrlParameter('subType2');
            }
            else {
              window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance + '&type=' + getUrlParameter('type') + '&subType1=' + getUrlParameter('subType1');
            }
          }
          else {
            window.location.href = $(".btn.btn-default.prev").attr("data-url") + '&distance=' + distance;
          }
      });

      $(document).on('change','#distance',function(){
           var distance = $("#distance").find(":selected").val();
           var parameters = '?';
           if (getUrlParameter('type') != undefined)
           {
             if (parameters == '?')
             {
               parameters = parameters + 'type=' + getUrlParameter('type');
             }
             else {
                parameters = parameters + '&type=' + getUrlParameter('type');
             }
           }
           if (getUrlParameter('subType1') != undefined)
           {
             if (parameters == '?')
             {
               parameters = parameters + 'subType1=' + getUrlParameter('subType1');
             }
             else {
                parameters = parameters + '&subType1=' + getUrlParameter('subType1');
             }
           }
           if (getUrlParameter('subType2') != undefined)
           {
             if (parameters == '?')
             {
               parameters = parameters + 'subType2=' + getUrlParameter('subType2');
             }
             else {
                parameters = parameters + '&subType2=' + getUrlParameter('subType2');
             }
           }
           if (parameters == '?')
           {
             parameters = parameters + 'distance=' + distance;
           }
           else {
              parameters = parameters + '&distance=' + distance;
           }
           window.location.href = window.location.pathname + parameters;
      });
  }




  // specific event
  else if ($("#page-type").val() == "event")
  {

    $(".login").click(function() {
      window.location.href = "/login";
    });

    $(".accept-invite").click(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({url: "invite/accept", type: "GET", data: {'event-id':$(this).attr('id')}, success: function(result){
        if (result['Success'] == 'False')
        {
          alert(result['Error']);
        }
        else {
          location.reload();
        }
      }});
    });

    $(".request-invite").click(function() {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({url: "request/invite", type: "POST", data: {'event-id':$(this).attr('id')}, success: function(result){
        if (result['Success'] == 'False')
        {
          alert(result['Error']);
        }
        else {
          location.reload();
        }
      }});
    });

    function scrollSmoothToBottom (id) {
       var div = document.getElementById(id);
       $('#' + id).animate({
          scrollTop: div.scrollHeight - div.clientHeight
       }, 500);
    }


      $("#event-chat-comment").keydown(function (e) {
          var code = (e.keyCode ? e.keyCode : e.which);
          if (code == 13) {
              $("#event-chat-submit").trigger('click');
              return true;
          }
      });

      var status = $("#status").val();
      var eventID = $("#event-id").val();
      if (status == "admin")
      {


        scrollSmoothToBottom('chat-panel');

        $("#transfer-admin-save").click(function() {
          var radioVal = $('#transfer-admin-radio input:radio:checked').val();
          if ($('#transfer-admin-radio input:radio:checked').length == 0)
          {
            $("#transfer-error").text("Please Select A Value");
            return false;
          }
          else {
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({
              type: "POST",
              url: "ownership",
              data: "trainer=" + radioVal + "&event-id=" + eventID,
              success : function(response){
                if (response['Success'] == 'True')
                {
                  location.reload()
                }
                else {
                  alert(response['Error']);
                }
              }

            });
          }

        });



        $("#confirm-disband-event").click(function() {

          $.post("delete",
                 {
            'event-id': eventID,
            '_token': $('meta[name="csrf-token"]').attr('content')
          },
                 function(data){
            if (data['Success'] == 'False')
            {
              alert(data['Error']);
            }
            else {
              window.location.href = "/dashboard";
            }
          });

        });

        $("#event-invite-request").click(function() {
          var trainer = $("#trainer-name-input").val();

          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
            type: "POST",
            url: "invite",
            data: "event-id=" + eventID + "&trainer=" + trainer,
            success : function(response){
              if (response['Success'] == 'True')
              {
                alert('Successfully invited ' + trainer + ' to event');
              }
              else {
                alert(response['Error']);
              }
            }

          });

        });

        $("#event-chat-submit").click(function() {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
            type: "POST",
            url: "chat/submit",
            data: "event-id=" + eventID + "&comment=" + $("#event-chat-comment").val(),
            success : function(response){
              if (response['Success'] == 'False')
              {
                alert(response['Error']);
              }
            }

          });
        });
      }

      else if (status == "user")
      {

        scrollSmoothToBottom('chat-panel');

        $("#event-chat-submit").click(function() {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
            type: "POST",
            url: "chat/submit",
            data: "event-id=" + eventID + "&comment=" + $("#event-chat-comment").val(),
            success : function(response){
              if (response['Success'] == 'False')
              {
                alert(response['Error']);
              }
            }

          });
        });

        $("#leave-event-button").click(function() {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
             type: "POST",
             url: "leave",
             data: "event-id=" + eventID,
             success : function(response){
               if (response['Success'] == 'False')
               {
                 if (response['Validation'] == 'True')
                 {
                    alert(response['Error']);
                 }
                 else {
                   alert(response['Errors']);
                 }
               }
               else {
                 window.location.href = "/";
               }
             }
          });
      	});

      }

      else {

        $("#request-invite-button").click(function() {

          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
            type: "POST",
            url: "request/invite",
            data: "event-id=" + eventID,
            success : function(response){
              if (response['Success'] == 'True')
              {
                alert('Request invite successfully');
              }
              else {
                alert(response['Error']);
              }
            }

          });
        });
      }
  }
});

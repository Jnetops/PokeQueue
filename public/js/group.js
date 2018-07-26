
$(document).ready(function() {

  // specific group
  if ($("#page-type").val() == 'group')
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

      $.ajax({url: "invite/accept", type: "GET", data: {'group-id':$(this).attr('id')}, success: function(result){
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

      $.ajax({url: "request/invite", type: "POST", data: {'group-id':$(this).attr('id')}, success: function(result){
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

      $("#group-chat-comment").keydown(function (e) {
          var code = (e.keyCode ? e.keyCode : e.which);
          if (code == 13) {
              $("#group-chat-submit").trigger('click');
              return true;
          }
      });

      if ($("#state").val() == "queued")
      {
        if ($("#group-time").text().indexOf("hours") >= 0)
        {
          var timeSplit = $("#group-time").text().split(" hours");
          var hour = timeSplit[0];
          var timeSplit2 = timeSplit[1].split(" min");
          var min = timeSplit2[0];
          var timeSplit3 = timeSplit2[1].split(" sec");
          var sec = timeSplit3[0];
        }
        else if ($("#group-time").text().indexOf("hour") >= 0) {
          var timeSplit = $("#group-time").text().split(" hour");
          var hour = timeSplit[0];
          var timeSplit2 = timeSplit[1].split(" min");
          var min = timeSplit2[0];
          var timeSplit3 = timeSplit2[1].split(" sec");
          var sec = timeSplit3[0];
        }
        else {
          var hour = "";
          var timeSplit2 = $("#group-time").text().split(" min");
          var min = timeSplit2[0];
          var timeSplit3 = timeSplit2[1].split(" sec");
          var sec = timeSplit3[0];
        }

        function incTime()
        {
          sec++;
          if (parseInt(sec) == 60)
          {
            if (parseInt(min) == 59)
            {
              min = "0";
              sec = "0";
              if (hour == "")
              {
                hour = "1";
              }
              else {
                hour = String(parseInt(hour) + 1);
              }
            }
            else {
              sec = "0";
              min = String(parseInt(min) + 1);
            }

            if (hour == "")
            {
              $("#group-time").text(min + " min " + "0 sec");
            }
            else if (parseInt(hour) >= 2) {
              $("#group-time").text(hour + " hours " + min + " min " + "0 sec");
            }
            else {
              $("#group-time").text(hour + " hour " + min + " min " + "0 sec");
            }
          }
          else {
            if (hour == "")
            {
              $("#group-time").text(min + " min " + sec + " sec");
            }
            else if (parseInt(hour) >= 2) {
              $("#group-time").text(hour + " hours " + min + " min " + sec + " sec");
            }
            else {
              $("#group-time").text(hour + " hour " + min + " min " + sec + " sec");
            }
          }
        }
        setInterval(incTime, 1000);
      }

    	var status = $("#status").val();
    	var groupID = $("#group-id").val();

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
    			 data: "trainer=" + radioVal + "&group-id=" + groupID,
    			 success : function(response){
    				if (response['Success'] == 'False')
    				{
    				  alert(response['Error']);
    				}
    			 }

    		  });
    		}

    	  });



    	  $("#confirm-finalize-group").click(function() {
      		$.ajaxSetup({
      			headers: {
      				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      			}
      		});

      		$.ajax({
      		   type: "POST",
      		   url: "finalize",
      		   data: "group-id=" + groupID,
      		   success : function(response){
        			 if (response['Success'] == 'False')
        			 {
        			   alert(response['Error']);
        			 }
        			 else {
        			   location.reload();
        			 }
      		   }
      		});
    	  });

        $("#confirm-queue-group").click(function() {
      		$.ajaxSetup({
      			headers: {
      				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      			}
      		});

      		$.ajax({
      		   type: "POST",
      		   url: "requeue",
      		   data: "group-id=" + groupID,
      		   success : function(response){
        			 if (response['Success'] == 'False')
        			 {
        			   alert(response['Error']);
        			 }
        			 else {
        			   location.reload();
        			 }
      		   }
      		});
    	  });

    	  $("#confirm-disband-group").click(function() {
    		$.ajaxSetup({
    			headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    			}
    		});

    		$.ajax({
    		   type: "POST",
    		   url: "disband",
    		   data: "group-id=" + groupID,
    		   success : function(response){
    			 if (response['Success'] == 'False')
    			 {
    			   alert(response['Error']);
    			 }
    			 else {
    			   window.location.href = "/";
    			 }
    		   }

    		});

    	  });

    	  $("#group-invite-request").click(function() {
    		var trainer = $("#trainer-name-input").val();

    		$.ajaxSetup({
    			headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    			}
    		});

    		$.ajax({
    		   type: "POST",
    		   url: "invite",
    		   data: "group-id=" + groupID + "&trainer=" + trainer,
    		   success : function(response){
    			 if (response['Success'] == 'True')
    			 {
    			   alert(trainer + ' invited successfully');
    			 }
    			 else {
    			   alert(response['Error']);
    			 }
    		   }

    		});

    	  });

    	  $("#group-chat-submit").click(function() {
    		$.ajaxSetup({
    			headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    			}
    		});

    		$.ajax({
    		   type: "POST",
    		   url: "chat/submit",
    		   data: "group-id=" + groupID + "&comment=" + $("#group-chat-comment").val(),
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

        $("#leave-group-button").click(function() {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
             type: "POST",
             url: "leave",
             data: "group-id=" + groupID,
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

    	  scrollSmoothToBottom('chat-panel');

    	  $("#group-chat-submit").click(function() {
    		$.ajaxSetup({
    			headers: {
    				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    			}
    		});


    		$.ajax({
    		   type: "POST",
    		   url: "chat/submit",
    		   data: "group-id=" + groupID + "&comment=" + $("#group-chat-comment").val(),
    		   success : function(response){
    			  if (response['Success'] == 'False')
    			  {
    				alert(response['Error']);
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
    		   data: "group-id=" + groupID,
    		   success : function(response){
    			 if (response['Success'] == 'True')
    			 {
    			   alert('Invite sent successfully');
    			 }
    			 else {
    			   alert(response['Error']);
    			 }
    		   }

    		});
    	  });
    	}
  }




  // create group
  else if ($("#page-type").val() == "create-group")
  {
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
              $(".pokemon-farming").text("Group Type: Invalid Farm Selection");
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
          $(".type").text("Group Type: Invalid Type Selection");
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
    }

    $( "#type,#teams,#pokemon-farming,#nest-farming" ).change(function() {
      hideShowTypes();
    });

    $("#queue").click(function() {
        var tCheck = TypeCheck();
        if (tCheck == true) {
          if ($("#city").find(':selected').val() != "" && $('#state').find(":selected").val() != "" && $("#address").val() != "") {
            $('#queue-form').submit();
          }
          else {
            alert('Invalid address infomation supplied');
          }
        }
        else {
          alert('Invalid types supplied');
        }
    });
  }



  // all groups
  else if ($("#page-type").val() == "all-groups")
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
      else if ($("#type").val() == "4") {
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
      else if ($("#type").val() == "4") {
        var typeParams = "?type=" + $("#type").val();
      }
      else {
        alert('Please Make Filter Selections First!');
        contRedir = false;
      }

      var distance = $("#distance").find(":selected").val();

      typeParams = typeParams + "&distance=" + distance;
      if (contRedir == true)
      {
          window.location.href = "/groups" + typeParams;
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
});

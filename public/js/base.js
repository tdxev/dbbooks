/**
* Send a ajax post request to server, call the function callback and pass
* as arguments server respons object and parameter send to the server
* 
* @param params parameters that will be sent to server 
* @param callback function that will be call when server responde
* @requires 
*/
function ajax(params, callback, display_errors)
{
  // by default display error message to user
  if (display_errors == null)
  {
    display_errors = true;
  }
    
  $.ajax(
  {
    url: document.app_baseurl + "/ajax",
    dataType: 'json',
    type:'post',
    data:{'data' : JSON.stringify(params)},
    success: function(server_response)
    {
      if(server_response.error == 0)
      {
        console.info("[i] AJAX");
        callback(server_response, params);
      }
      else
      {
        console.info("[e] AJAX");
        if (display_errors)
        {
          display_message('Error', server_response.msg);
        }
      }
    }
  });
}

/**
* Display message to screen
* 
* @param title Title of the dialog box
* @param message Message that will be display in the dialog box
*/
function display_message(title, message)
{
  $('body').append('<div id="usermsg"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>' + message + '</p></div>');
  $("#usermsg").dialog({
    'modal' : true,
    'title' : title,
    'close' : function(event, ui){
      $(this).remove();
    },
    'buttons': {
      'Ok' : function(){$(this).dialog( "close" );}
    }
  })
}
  
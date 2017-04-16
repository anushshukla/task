

$( document ).ready(function() {

  $.ajaxSetup({
    url: window.location
    ,method : 'GET'
    ,type : 'GET'
    // array of input issue
    ,traditional : false
    ,processData : true
    ,ifModified : false
    ,crossOrigin: false
    ,global : true
    ,async : true 
    ,cache : true
    ,data : {}
    ,context : {}
    ,contentType : "application/x-www-form-urlencoded"
    ,dataType : 'application/json'
    ,scriptCharset : "utf-8"
    ,username : null
    ,password : null
    ,timeout : 500
    /*
    ,jsonp : 'callback'
    ,dataFilter : function(data,type) {
    }
    ,beforeSend : function(xhr,status) {
    }
    ,complete : function() {
    }
    ,error : function(xhr,status,error) {
    }
    ,success : function(result,status,xhr) {
    }
    ,xhr : function() {
    }
    ,jsonpCallback : function() {
    }
    */
  });

  $( document ).ajaxSend(function(event, request, settings) {
    if(settings.form) {
      $(settings.form).find('[type=submit]:enabled').prop('disabled',true);
    }
  });

  $( document ).ajaxStart(function() {
  });

  $( document ).ajaxStop(function() {
    $( "#loading" ).hide();
  });

  $( document ).ajaxComplete(function(event, request, settings) {
    if(settings.form) {
      // avoid double click
      setTimeout(function() {
        $(settings.form).find('[type=submit]:disabled').prop('disabled',false);
      },100)
    }
  });

  $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
    $('.modal').modal('hide');
    BootstrapDialog.error('Whoops, something went wrong!');
  });

  $( document ).ajaxSuccess(function( event, request, settings ) {
    $('.modal').modal('hide');
    BootstrapDialog.error('Operation was successfuly');
  });
  
  ajaxParams = {
    method: 'POST'  
    url: window.locayion.href
  }

  ajaxParams.method = 'OPTIONS';
  $.ajax(ajaxParams);

  ajaxParams.method = 'POST';
  $.ajax(ajaxParams);

  ajaxParams.method = 'GET';
  $.ajax(ajaxParams);

  ajaxParams.method = 'PUT';
  $.ajax(ajaxParams);

  ajaxParams.method = 'DELETE';
  $.ajax(ajaxParams);

});
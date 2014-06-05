mw.ajaxState = {
    time: 250,
    tooMuchTime:2250,
    isGoing:false
}

$(document).ready(function(){
  mw.session.checkInit();
  $(mwd.body).ajaxStart(function(){
    mw.ajaxState.isGoing = true;
    var body = $(this);
    setTimeout(function(){
       mw.ajaxState.isGoing ? body.addClass('loading') : '';
    }, mw.ajaxState.time);
    setTimeout(function(){
       mw.ajaxState.isGoing ? body.addClass('still-loading') : '';
    }, mw.ajaxState.tooMuchTime);
  });

  $(mwd.body).ajaxStop(function(){
    mw.ajaxState.isGoing = false;
    $(this).removeClass('loading').removeClass('still-loading');
  });

  $(window).bind('unload', function(){
       $(mwd.body).addClass('loading');
  });

});
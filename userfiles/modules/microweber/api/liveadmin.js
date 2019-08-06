mw.ajaxState = {
    time: 250,
    tooMuchTime:2250,
    isGoing:false
}

$(document).ready(function(){
  mw.session.checkInit();
  mw.$(mwd.body).ajaxStart(function(){
    mw.ajaxState.isGoing = true;
    var body = mw.$(this);
    setTimeout(function(){
       mw.ajaxState.isGoing ? body.addClass('loading') : '';
    }, mw.ajaxState.time);
    setTimeout(function(){
       mw.ajaxState.isGoing ? body.addClass('still-loading') : '';
    }, mw.ajaxState.tooMuchTime);
  });

  mw.$(mwd.body).ajaxStop(function(){
    mw.ajaxState.isGoing = false;
    mw.$(this).removeClass('loading').removeClass('still-loading');
  });

  mw.$(window).bind('unload', function(){
       mw.$(mwd.body).addClass('loading');
  });

});

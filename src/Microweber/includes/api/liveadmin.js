

mw.ajaxState = {
    time: 250,
    tooMuchTime:2250,
    isGoing:false
}








    $(document).ready(function(){

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




      /*TEMP*/
      $(mwd.body).append('<div class="vl"></div><div class="hl"></div>');


       $("#UITU").prepend('<span class="mw-ui-btn" onclick="$(mwd.body).toggleClass(\'cross\')">Cross</span>');

       $(".vl,.hl").click(function(){
          $(mwd.body).toggleClass('cross');
       })
       $(mwd.body).mousemove(function(e){
          $(".vl").css('left', e.pageX-$(window).scrollLeft());
          $(".hl").css('top', e.pageY-$(window).scrollTop());
       });

       /* END of TEMP*/


       


    });



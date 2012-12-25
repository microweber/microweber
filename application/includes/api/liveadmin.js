

mw.ajaxState = {
    time: 250,
    tooMuchTime:2250,
    isGoing:false
}


mw.liveadmin = {
    menu:{
        size:function(minusIndex){
          var minusIndex = minusIndex || 50;
            //left side
            var liquid = mw.$("#mw-menu-liquify");
            if(liquid.size()>0){
              var liquidleft = liquid.offset().left;
              var liquidwidth = liquid.width();

              //right side
              var right = mw.$("#mw-toolbar-right");
              var right_width = right.width();

              var w = $(window).width();
              liquid.css('maxWidth', w - liquidleft-right_width - minusIndex);
              mw.liveadmin.menu.dropItems(true);
            }
        },
        dropItems:function(when){
          if(when){ //not too responsive
            var context = mwd.getElementById('mw_tabs');
            var top = context.offsetTop;
            var items = context.getElementsByTagName('li'), l=items.length, i=0;
            var html = '';
            for( ; i<l; i++){
                var item = items[i];
                if(item.offsetTop > top){
                  mw.tools.addClass(item, 'dropped');
                  var span='<span class="'+ item.className +'">';
                  var html = html + span + item.innerHTML + '</span>';
                }
                else{
                  mw.tools.removeClass(item, 'dropped');
                }
            }
            html === '' ? mw.$("#menu-dropdown").hide() : mw.$("#menu-dropdown").show();
            mw.$('#menu-dropdown-nav').html(html);
          }
        }
    }
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



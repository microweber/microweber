

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

mw.simpletabs = function(root){
  var root = root || mwd;
  mw.$(".mw_simple_tabs_nav, .mw-tabs", root).each(function(){
    if(!$(this).hasClass('activated')){
        $(this).addClass('activated')
        if(!$(this).hasClass('by-hash')){
            var parent = $(mw.tools.firstParentWithClass(this, 'mw_simple_tabs'));
            parent.children(".tab").addClass("semi_hidden");
            parent.children(".tab").eq(0).removeClass("semi_hidden");
            $(this).find("a").eq(0).addClass("active");
            $(this).find("a").click(function(){
                mw.simpletab.set(this);
                return false;
            });
        }
        else{

        }
    }
  });
}

mw.simpletab = {
  set:function(el){
      if(!$(el).hasClass('active')){
        var ul = mw.tools.firstParentWithClass(el, 'mw_simple_tabs_nav');

        if(ul===null || typeof ul === 'undefined' || !ul){ return false; }
        var master = mw.tools.firstParentWithClass(ul, 'mw_simple_tabs');
        $(ul.querySelector('.active')).removeClass('active');
        $(el).addClass('active');
        var index = mw.tools.index(el, ul);
        $(master).children('.tab').addClass('semi_hidden');
        $(master).children('.tab').eq(index).removeClass('semi_hidden');
      }
  }
}

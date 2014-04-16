mw.admin = {
    scrollBoxSettings:{
        height:'auto',
        size:5,
        distance:5
    },
    scrollBox:function(selector, settings){
        var settings = $.extend({}, mw.admin.scrollBoxSettings, settings);
        mw.$(selector).slimScroll(settings);
    },
    contentScrollBoxHeightFix:function(node){
      var minus = 0, exceptor = mw.tools.firstParentWithClass(node, 'scroll-height-exception-master');
      if( !exceptor ) {  return $(window).height(); }
      mw.$('.scroll-height-exception', exceptor).each(function(){
        d(minus)
        var minus = minus + $(this).outerHeight();
      });
      d(minus)
      return $(window).height() - minus;
    },
    contentScrollBox:function(selector, settings){
       var el = mw.$(selector)[0];
       if(typeof el === 'undefined'){ return false; }
       mw.admin.scrollBox(el, settings);
       el.style.height = mw.admin.contentScrollBoxHeightFix(el) + 'px';
       $(window).bind('resize', function(){
            var newheight =  mw.admin.contentScrollBoxHeightFix(el)
            el.style.height = newheight + 'px';
            el.parentNode.style.height = newheight + 'px';
            $(el).slimscroll();
       });
    }
}


$(mwd).ready(function(){


});

$(mww).bind('load', function(){


    mw.admin.contentScrollBox('.fixed-side-column-container');
    mw.admin.contentScrollBox('#main-menu', {color:'white'});


});



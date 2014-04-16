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
    contentScrollBoxHeightMinus:0,
    contentScrollBoxHeightFix:function(node){
      mw.admin.contentScrollBoxHeightMinus = 0, exceptor = mw.tools.firstParentWithClass(node, 'scroll-height-exception-master');
      if( !exceptor ) {  return $(window).height(); }
      mw.$('.scroll-height-exception', exceptor).each(function(){
        mw.admin.contentScrollBoxHeightMinus = mw.admin.contentScrollBoxHeightMinus + $(this).outerHeight();
      });
      return $(window).height() - mw.admin.contentScrollBoxHeightMinus;
    },
    contentScrollBox:function(selector, settings){
       var el = mw.$(selector)[0];
       if(typeof el === 'undefined'){ return false; }
       mw.admin.scrollBox(el, settings);
       var newheight = mw.admin.contentScrollBoxHeightFix(el)
       el.style.height = newheight + 'px';
       el.parentNode.style.height = newheight  + 'px'
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



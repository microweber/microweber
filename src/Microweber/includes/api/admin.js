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
        mw.admin.contentScrollBoxHeightMinus = mw.admin.contentScrollBoxHeightMinus + $(this).outerHeight(true);
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
    },
    treeboxwidth:function(){
      var w = mw.$('.fixed-side-column').width();
      mw.$('.tree-column').width(w);
    },
    _treeboxwidth:function(){
        var i = 0, all = mwd.querySelectorAll('#pages_tree_toolbar li.active-bg > a .pages_tree_link_text'), l = all.length, max = 0;
        for( ; i<l; i++){
          var ch = all[i].textContent.length;
          if(ch > 15){
            var max = 15;
          }
        }
        if(max === 15){
            var w = mw.$('.fixed-side-column').width();
            mw.$('.tree-column, .fixed-side-column').width(310);
        }
        else{
            mw.$('.tree-column, .fixed-side-column').width(210);
        }
    }
}


$(mwd).ready(function(){
   mw.admin.treeboxwidth();
});

$(mww).bind('load', function(){

    mw.admin.contentScrollBox('.fixed-side-column-container');
    mw.admin.contentScrollBox('#main-menu', {color:'white'});
    mw.admin.treeboxwidth();

    mw.on.moduleReload('pages_tree_toolbar', function(){
       mw.admin.treeboxwidth();
       setTimeout(function(){
           mw.admin.treeboxwidth();
       }, 90);
    });

   var create_content_btn = mwd.querySelectorAll('.create-content-btn');

   if(create_content_btn.length !== 0){
     $(create_content_btn).each(function(){
       this.mwtooltip = mw.tooltip({
         position:$(this).dataset('tip') != '' ? $(this).dataset('tip') : 'bottom-center',
         content:mw.$('#create-content-menu').html(),
         element:this,
         skin:'dark'
       });
       this.mwtooltip.style.display = 'none';

       $(this).timeoutHover(function(){
         $(this.mwtooltip).show();
       }, function(){
          if(this.mwtooltip.originalOver === false){
            $(this.mwtooltip).hide();
          }
       });


       $(this.mwtooltip).timeoutHover(function(){
          $(this).show();
       }, function(){
          if(this.originalOver === false){

            $(this).hide();
          }
       });


     });



   }


});

$(mww).bind('hashchange', function(){
    mw.admin.treeboxwidth();
});





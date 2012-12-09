








set_main_height = function(){
  mw.$("#mw-admin-container").css("minHeight", $(window).height()-41)
}



mw.admin = {
  sidebar:function(){
    if(mw.$("#mw_edit_page_left").length > 0){
        $("#mw-admin-container").addClass('has_sidebar');
        $("#mw-admin-container").css('backgroundPosition',  '-'+(500-$("#mw_edit_page_left").width()) + 'px 0');
    }
  },
  scale:function(obj, to){
    var css = mw.CSSParser(obj);
    var win = $(window).width();
    var sum = win - css.get.padding(true).left - css.get.padding(true).right - css.get.margin(true).right - css.get.margin(true).left;
    if(!to){
      obj.style.width = sum + 'px';
    }
    else{
      obj.style.width = (sum-$(to).outerWidth(true)) + 'px';
    }
  },
  menu:{
      size:function(){
          //left side
          var liquid = mw.$("#mw-menu-liquify");
          if(liquid.size()>0){
            var liquidleft = liquid.offset().left;
            var liquidwidth = liquid.width();

            //right side
            var right = mw.$("#mw-toolbar-right");
            var right_width = right.width();

            var w = $(window).width();
            liquid.width(w-liquidleft-right_width-50);
            mw.admin.menu.dropItems(true);
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
                var html = html + item.innerHTML;
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


urlParams = mw.url.mwParams(window.location.href);


$(window).bind('load resize', function(){
    mw.admin.menu.size();
    set_main_height();
    if(urlParams.view === 'dashboard' || urlParams.view === undefined){
      var visitstable = mwd.getElementById('visits_info_table');
      var visitsnumb = mwd.getElementById('users_online');
      mw.admin.scale(visitstable, visitsnumb);
    }

});


$(document).ready(function(){

   mw.$('#mw-menu-liquify').hide();
   mw.admin.sidebar();
   mw.$('#mw-menu-liquify').show();
   mw.admin.menu.size();
   $(window).bind('hashchange', function(){
     mw.admin.sidebar();
   });




});





$(document).ready(function(){


$(window).resize(function(){
  set_main_height()
});


    mw.admin.sidebar()

   $(window).bind('hashchange', function(){
     mw.admin.sidebar();
   });

});


$(window).load(function(){
      set_main_height();
});


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
    var sum = win - parseFloat(css.get.padding().left) - parseFloat(css.get.padding().right) - parseFloat(css.get.margin().right) - parseFloat(css.get.margin().left);
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
        if(when){ //no too responsive
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

    if(urlParams.view === 'dashboard'){
      var visitstable = mwd.getElementById('visits_info_table');
      var visitsnumb = mwd.getElementById('users_online');
      mw.admin.scale(visitstable, visitsnumb);
    }


});



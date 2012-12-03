$(document).ready(function(){





$(window).resize(function(){
  set_main_height()
});


});


$(window).load(function(){
      set_main_height()
});


set_main_height = function(){
  mw.$("#mw-admin-container").css("minHeight", $(window).height()-41)
}



mw.admin = {
  menu:{
      size:function(){
          //left side
          var liquid = mw.$("#mw-menu-liquify");
          var liquidleft = liquid.offset().left;
          var liquidwidth = liquid.width();

          //right side
          var right = mw.$("#mw-toolbar-right");
          var right_width = right.width();

          var w = $(window).width();

          liquid.width(w-liquidleft-right_width-50);

          mw.admin.menu.dropItems(true);

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
      },
      init:function(){
          $(window).bind('load resize', function(){
             mw.admin.menu.size();
          });
      },

    }
}


mw.admin.menu.init();
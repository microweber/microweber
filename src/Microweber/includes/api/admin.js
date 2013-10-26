set_main_height = function(){
  mw.$("#mw-admin-container").css("minHeight", $(window).height()-41)
}



mw.admin = {
  scale:function(obj, to){
    if(obj === null) return false;
    var css = mw.CSSParser(obj);
    var win = $(window).width();
    var sum = win - css.get.padding(true).left - css.get.padding(true).right - css.get.margin(true).right - css.get.margin(true).left;
    if(!to){
      obj.style.width = sum + 'px';
    }
    else{
      obj.style.width = (sum-$(to).outerWidth(true)) + 'px';
    }
  }
}


urlParams = mw.url.mwParams(window.location.href);


$(window).bind('load resize', function(){




    set_main_height();
    if(urlParams.view === 'dashboard' || urlParams.view === undefined){
      /*
      var visitstable = mwd.getElementById('visits_info_table');
      var visitsnumb = mwd.getElementById('users_online');
      mw.admin.scale(visitstable, visitsnumb);  */
    }

});


$(document).ready(function(){


   mw.tools.sidebar();

   $(window).bind('hashchange', function(){
     mw.tools.sidebar();
   });


 mmwgel = mwd.createElement('div');



});







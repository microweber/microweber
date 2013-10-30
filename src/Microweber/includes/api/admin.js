set_main_height = function(){
  var h = Math.max($(mwd.body).height(), $(window).height());
  mw.$("#mw_edit_page_left").css("minHeight", h-41)
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
$(window).bind('load resize ajaxStop', function(){
    set_main_height();
});
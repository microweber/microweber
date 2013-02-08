


mw.cache = {
   get:function(key){
    var item = localStorage.getItem(key);
    return item !== null ? item : undefined;
   },
   save:function(key, val){
     return localStorage.setItem(key, val)
   },
   remove:function(key){
     return localStorage.removeItem(key);
   },
   clearAll:function(){
     for (var x in localStorage){
        mw.cache.remove(x);
     }
   }
}


_HResizer = mwd.createElement('div');
_HResizer.className = 'mw-horizontal-resizer';

_VResizer = mwd.createElement('div');
_VResizer.className = 'mw-vertical-resizer';
;
$(_HResizer).draggable({
    axis:'x',
    drag: function( event, ui ) {
      var f = $(this).data("for");
      var w =  $(this).offset().left - $(f).offset().left;
      $(f).width(w);
    }
});
$(_VResizer).draggable({
    axis:'y',
    drag: function( event, ui ) {
      var f = $(this).data("for");
      var h =  $(this).offset().top - $(f).offset().top;
      $(f).height(h);
    }
});
mw.HResizer = function(){
   $(window).bind("onModuleOver", function(a,el){
    var off = $(el).offset()
    var w = $(el).width();
    $(_HResizer).css({
        top:off.top,
        left:off.left+w,
    }).data("for", el)
   });
}
mw.VResizer = function(){
   $(window).bind("onModuleOver", function(a,el){
    var off = $(el).offset()
    var h = $(el).height();
    $(_VResizer).css({
        top:off.top+h,
        left:off.left,
    }).data("for", el)
   });
}

$(document).ready(function(){

  mwd.body.appendChild(_HResizer)
  mw.HResizer();
    mwd.body.appendChild(_VResizer)
  mw.VResizer();

});













/*

$(document).ready(function(){

   $("#mw_tabs a").live("click", function(){
      CachePage(this);
      return false;
   });

 });

CachePage = function(el){
    var href = $(el).attr('href');
    var cache = mw.cache.get(href);
    if(cache !== undefined){
      history.pushState(href, document.title, "/1k/admin/view:"+mw.url.mwParams(href).view);
      $(window).trigger("popstate");

    }
    else {
      window.location.href = href;
    }
    return false;
}

$(window).bind('popstate',  function(event) {
   mwd.body.innerHTML = mw.cache.get(window.location.href)
});

$(window).load(function(){
  mw.cache.save(window.location.href, mwd.body.innerHTML)
});

*/



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

mw.supports = {};


(function(o){

   var t = mwd.createElement('div');
   var c = mwd.createElement('canvas');

   o.mouseenter = t.onmouseenter === null ? true : false;
   o.localstorage = o.localStorage = 'localStorage' in window;
   o.canvas = !!c.getContext;




   delete t;
   delete c;

})(mw.supports);







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
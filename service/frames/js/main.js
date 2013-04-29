mw = {}
mwd = document;

mw.qsas = mwd.querySelector;

d = function(a){return console.log(a)}

mw.$ = function(selector, context) {
  var context = context || mwd;
  if (mw.qsas) {
    if (typeof selector === 'string') {
      try {
        return jQuery(context.querySelectorAll(selector));
      } catch (e) {
        return jQuery(selector, context);
      }
    } else {
      return jQuery(selector, context);
    }
  } else {
    return jQuery(selector, context);
  }
};



window.onmessage = function(e){
  var data = JSON.parse(e.data);
  if(typeof data.user !== 'undefined'){
    mw.$("#username").html(data.user);
  }
}
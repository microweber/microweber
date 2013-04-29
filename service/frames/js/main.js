mw = {}
mwd = document;

mw.qsas = mwd.querySelector;

d = function(a){ return console.log(a) }





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


mw.serializeFields =  function(id){
      var el = mw.$(id);
      fields = "input[type='text'], input[type='email'], input[type='number'], input[type='password'], input[type='hidden'], textarea, select, input[type='checkbox']:checked, input[type='radio']:checked";
      var data = {}
      $(fields, el).each(function(){
          if(!$(this).hasClass('no-post')){
            var el = this, _el = $(el);
            var val = _el.val();
            var name = el.name;
            if(el.name.contains("[]")){
              try {
                 data[name].push(val);
              }
              catch(e){
                data[name] = [val];
              }
            }
            else{
              data[name] = val;
            }
          }
      });
      return data;
 }



window.onmessage = function(e){
  var data = JSON.parse(e.data);
  if(typeof data.user !== 'undefined'){
    mw.$("#username").html(data.user);
  }
}

mw.post = function(form, callback){
    var data = mw.serializeFields(form);
    $.post("query.php", data, function(a){
        if(typeof callback === 'function'){
            callback.call(a);
        }
    })

}










mw.on = {
  _onmodules : [],
  _onmodules_funcs : [],
  moduleReload : function(id, c, trigger){
     if(trigger){
          var index = mw.on._onmodules.indexOf(id);
          if(index != -1){
            mw.on._onmodules_funcs[index].call(mwd.getElementById(id));
          }
          return false;
     }
     if(mw.is.func(c)){
       mw.on._onmodules.push(id);
       mw.on._onmodules_funcs.push(c);
     }
     else if(c==='off'){
       var index = mw.on._onmodules.indexOf(id);
       if(index != -1){
        mw.on._onmodules.splice(index, 1);
        mw.on._onmodules_funcs.splice(index, 1);
       }
     }
  },
  _hashrec : {},
  _hashparams : [],
  _hashparam_funcs : [],
  hashParam : function(param, callback, trigger){
    if(trigger==true){
        var index = mw.on._hashparams.indexOf(param);
        if(index != -1){
          var hash = mw.hash();
          var params = mw.url.getHashParams(hash);
          if(mw.is.string(params[param])){
              mw.on._hashparam_funcs[index].call(params[param]);
          }
        }
    }
    else{
        mw.on._hashparams.push(param);
        mw.on._hashparam_funcs.push(callback);
    }
},
hashParamEventInit:function(){
  var hash = mw.hash();
  var params = mw.url.getHashParams(hash);
  if(hash==='' || hash==='#' || hash ==='#?'){
    var len = mw.on._hashparams.length, i=0;
    for( ; i < len; i++){
        mw.on.hashParam(mw.on._hashparams[i], "", true);
    }
  }
  else{
    for(var x in params){
        if(params[x] !== mw.on._hashrec[x] || mw.on._hashrec[x]===undefined){
            mw.on.hashParam(x, "", true);
        }
    }
  }
  mw.on._hashrec = params;
},
DOMChange:function(element, callback){
    element.addEventListener("DOMCharacterDataModified", function(){
        callback.call(this);
    }, false);
    element.addEventListener("DOMNodeInserted", function(){
        callback.call(this);
    }, false);
 },
 _stopWriting:null,
 stopWriting:function(el,callback){
    typeof mw.on._stopWriting === 'number' ? clearTimeout(mw.on._stopWriting) : '';
     mw.on._stopWriting = setTimeout(function(){
       callback.call(el);
     }, 600);
 }
}





$(window).bind("hashchange load", function(){
   mw.on.hashParamEventInit();

   var hash =  mw.hash();
   if(hash.contains("showpostscat")){
      mw.$(".manage-toolbar-top").show();
      mw.$("html").addClass("showpostscat");
   }
   else{
      mw.$(".manage-toolbar-top").hide();
      mw.$("html").removeClass("showpostscat");
   }


});


mw.hash = function(b){
  return b===undefined ? window.location.hash : window.location.hash = b;
}





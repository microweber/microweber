


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
  hashParam : function(param, callback, trigger, isManual){
    if(isManual){
        var index = mw.on._hashparams.indexOf(param);
        if(mw.on._hashparam_funcs[index]!==undefined){
          mw.on._hashparam_funcs[index].call(false);
        }
        return false;
    }
    if(trigger==true){
        var index = mw.on._hashparams.indexOf(param);
        if(index != -1){
          var hash = mw.hash();
          var params = mw.url.getHashParams(hash);
          if(typeof params[param] === 'string'){
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
  if(typeof mw.url == 'undefined'){
    mw.require('url.js');
  }




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
    element.addEventListener("DOMAttrModified", function(e){
        var attr = e.attrName;
        if(attr != "contenteditable"){
           callback.call(this);
        }
    }, false);
 },
 _stopWriting:null,
 stopWriting:function(el,callback){
    typeof mw.on._stopWriting === 'number' ? clearTimeout(mw.on._stopWriting) : '';
     mw.on._stopWriting = setTimeout(function(){
       callback.call(el);
     }, 600);
 },
 scrollBarOnBottom : function(obj, distance, callback){
    if(typeof obj === 'function'){
       var callback = obj;
       var obj =  window;
       var distance = 0;
    }
    if(typeof distance === 'function'){
      var callback = distance;
      var distance = 0;
    }
    obj._pauseCallback = false;
    obj.pauseScrollCallback = function(){ obj._pauseCallback = true;}
    obj.continueScrollCallback = function(){ obj._pauseCallback = false;}
    $(obj).scroll(function(e){
      var h = obj === window ? mwd.body.scrollHeight : obj.scrollHeight;
      var calc = h - $(obj).scrollTop() - $(obj).height();
      if(calc <= distance && !obj._pauseCallback){
        callback.call(obj);
      }
    });
  }
}

mw.hashHistory = [window.location.hash]



$(window).bind("hashchange load", function(event){
   mw.on.hashParamEventInit();

   var hash =  mw.hash();
   if(hash.contains("showpostscat")){
   //   mw.$(".manage-toolbar-top").show();
      mw.$("html").addClass("showpostscat");
   }
   else{
   ///   mw.$(".manage-toolbar-top").hide();
      mw.$("html").removeClass("showpostscat");
   }


   if(event.type=='hashchange'){
     mw.hashHistory.push(mw.hash());
     //Check if current hash has lost params and bind the event with fasle
     var size = mw.hashHistory.length;
     var changes = mw.url.whichHashParamsHasBeenRemoved(mw.hashHistory[size-1], mw.hashHistory[size-2]), l=changes.length, i=0;
     if(l>0){
       for( ; i<l; i++){
         
          mw.on.hashParam(changes[i], "", true, true);
       }
     }
   }

});


mw.hash = function(b){ return b === undefined ? window.location.hash : window.location.hash = b; }

mw.__bindMultiple__objects = [];
mw.__bindMultiple__events = {};



mw.bindMultiple = function(object, event, func){  //Bind events without overwriting
    var dont_exists = mw.__bindMultiple__objects.indexOf(object) == -1;

    if(dont_exists){
       var len = mw.__bindMultiple__objects.push(object);
    }
    var pos = mw.__bindMultiple__objects.indexOf(object);

    if(mw.__bindMultiple__events[pos] === undefined){
       mw.__bindMultiple__events[pos] = [func];
    }
    else{
       mw.__bindMultiple__events[pos].push(func);
    }

    if(dont_exists){
      $(object).bind(event, function(){
          var pos = len-1;
          var funcs = mw.__bindMultiple__events[pos];
          for(var x in funcs){
             funcs[x].call(object, event);
          }
       });
    }
    return object;
}

$.fn.bindMultiple = function(event, callback){
    return this.each(function(){
       mw.bindMultiple(this, event, callback);
    });
}







mw.on = function(eventName, callback){
    eventName = eventName.trim();
    $.each(eventName.split(' '), function(){
        mw.$(mw._on._eventsRegister).on(this.toString(), callback);
    });
};
mw.trigger = function(eventName, paramsArray){
    return mw.$([mww, mw._on._eventsRegister]).trigger(eventName, paramsArray);
};

mw._on = {
  _eventsRegister:{},
  mouseDownAndUp:function(el, callback){
    var $el = mw.$(el);
    el = $el[0];
    $el.on('mousedown touchstart', function(){
      this.__downTime = new Date().getTime();
      (function(el){
        setTimeout(function(){
          el.__downTime = -1;
        }, 777);
      })(this);
    });
    $el.on('mouseup touchend', function(e){
      if(!!callback){
        callback.call(this, new Date().getTime()-this.__downTime, e)
      }
    });
  },
  onmodules : {},
  moduleReload : function(id, c, trigger){
      var exists;
     if(trigger){
          exists = typeof mw.on.onmodules[id] !== 'undefined';
          if(exists){
            var i = 0, l = mw.on.onmodules[id].length;
            for( ; i < l; i++){
               mw.on.onmodules[id][i].call(document.getElementById(id));
            }
          }
        return false;
     }
     if(typeof c === 'function'){
       exists = typeof mw.on.onmodules[id] !== 'undefined';
       if(exists){
          mw.on.onmodules[id].push(c);
       }
       else{
         mw.on.onmodules[id] = [c];
       }
     }
     else if(c==='off'){
        exists = typeof mw.on.onmodules[id] !== 'undefined';
        if(exists){
          mw.on.onmodules[id] = [];
        }
     }
  },
  _hashrec : {},
  _hashparams : this._hashparams || [],
  _hashparam_funcs : [],
  hashParam : function(param, callback, trigger, isManual){
    var index;
    if(isManual){
        index = mw.on._hashparams.indexOf(param);
        if (mw.on._hashparam_funcs[index] !== undefined){
          mw.on._hashparam_funcs[index].call(false, false);
        }
        return false;
    }
    if(trigger === true){
        index = mw.on._hashparams.indexOf(param);

        if(index !== -1){
          var hash = location.hash;
          var params = mw.url.getHashParams(hash);

          if (typeof params[param] === 'string' && mw.on._hashparam_funcs[index] !== undefined) {
              var pval = decodeURIComponent(params[param]);
              mw.on._hashparam_funcs[index].call(pval, pval);

          }
        }
    }
    else{
        mw.on._hashparams.push(param);
        mw.on._hashparam_funcs.push(callback);
    }
},
hashParamEventInit:function(){
  var hash = location.hash;
  var params = mw.url.getHashParams(hash);

  if(hash==='' || hash==='#' || hash ==='#?'){
    var len = mw.on._hashparams.length, i=0;
    for( ; i < len; i++){
        mw.on.hashParam(mw.on._hashparams[i], "", true);
    }
  }
  else{
    for(var x in params){
        if(params[x] !== mw.on._hashrec[x] || typeof mw.on._hashrec[x] === 'undefined'){
            mw.on.hashParam(x, "", true);
        }
    }
  }

  mw.on._hashrec = params;
},
DOMChangePause:false,
DOMChangeTime:1500,
DOMChange:function(element, callback, attr, a){
    attr = attr || false;
    a = a || false;

    element.addEventListener("input", function(e){
        if( !mw.on.DOMChangePause ) {
            if(!a){
                callback.call(this);
            }
            else{
                clearTimeout(element._int);
                element._int = setTimeout(function(){
                    callback.call(element);
                }, mw.on.DOMChangeTime);
            }

        }
    }, false);

    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    if(typeof MutationObserver === 'function'){
        var observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation){
            if( !mw.on.DOMChangePause ) {
                callback.call(mutation.target);
            }
          });
        });
        var config = { attributes: attr, childList: true, characterData: true };
        observer.observe(element, config);
    } else {
        element.addEventListener("DOMCharacterDataModified", function(e){
            if( !mw.on.DOMChangePause ) {
                if(!a){
                    callback.call(this);
                }
                else{
                    clearTimeout(element._int);
                    element._int = setTimeout(function(){
                        callback.call(element);
                    }, mw.on.DOMChangeTime);
                }

            }
        }, false);
        element.addEventListener("DOMNodeInserted", function(e){

            if(/*mw.tools.hasClass(e.target, 'element') || */mw.tools.hasClass(e.target, 'module') || mw.tools.hasParentsWithClass(e.target, 'module')){
                return false;
            }
            if( !mw.on.DOMChangePause ) {
                if(!a){
                    callback.call(this);
                }
                else{
                    clearTimeout(element._int);
                    element._int = setTimeout(function(){
                        callback.call(element);
                    }, mw.on.DOMChangeTime);
                }
            }
        }, false);

        if(attr){
            element.addEventListener("DOMAttrModified", function(e){

                var attr = e.attrName;
                if(attr !== "contenteditable"){
                    if( !mw.on.DOMChangePause ) {
                        if(!a){
                            callback.call(this);
                        }
                        else{
                            clearTimeout(element._int);
                            element._int = setTimeout(function(){
                                callback.call(element);
                            }, mw.on.DOMChangeTime);
                        }
                    }
                }
            }, false);
        }
    }

 },
 stopWriting:function(el, c){
    if(el === null || typeof el === 'undefined'){ return false; }
    if(!el.onstopWriting){
      el.onstopWriting = null;
    }
    clearTimeout(el.onstopWriting);
    el.onstopWriting = setTimeout(function(){
        c.call(el);
    }, 400);
 },
 scrollBarOnBottom : function(obj, distance, callback){
    if(typeof obj === 'function'){
       callback = obj;
       obj =  window;
       distance = 0;
    }
    if(typeof distance === 'function'){
      callback = distance;
      distance = 0;
    }
    obj._pauseCallback = false;
    obj.pauseScrollCallback = function(){ obj._pauseCallback = true;}
    obj.continueScrollCallback = function(){ obj._pauseCallback = false;}
    mw.$(obj).scroll(function(e){
      var h = obj === window ? document.body.scrollHeight : obj.scrollHeight;
      var calc = h - mw.$(obj).scrollTop() - mw.$(obj).height();
      if(calc <= distance && !obj._pauseCallback){
        callback.call(obj);
      }
    });
  },
  tripleClick : function(el, callback){
      var t, timeout = 199;
      el = el || window;
      el.addEventListener("dblclick", function () {
          t = setTimeout(function () {
              t = null;
          }, timeout);
      });
      el.addEventListener("click", function (e) {
          if (t) {
              clearTimeout(t);
              t = null;
              callback.call(el, e.target);
          }
      });
  },
  transitionEnd:function(el,callback){
    mw.$(el).bind('webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd otransitionend', function(){
        callback.call(el);
    });
  },
  ones:{ },
  one:function(name, c, trigger, isDone){
    if(trigger !== true){
      if(mw.on.ones[name] === undefined){
         mw.on.ones[name] = [c]
      }
      else{
         mw.on.ones[name].push(c);
      }
    }
    else{
       if(mw.on.ones[name] !== undefined){
          var i=0, l = mw.on.ones[name].length;
          for( ; i<l; i++){
              if(isDone === true){
                mw.on.ones[name][i].call('ready', 'ready');
              }
              else{
                mw.on.ones[name][i].call('start', 'start');
              }
          }
       }
    }
  },
  userIteractionInitRegister: new Date().getTime(),
  userIteractionInit: function(){
      var max = 378;
      mw.$(mwd).on('mousemove touchstart click keydown resize ajaxStop', function(){
          var time = new Date().getTime();
          if((time - mw._on.userIteractionInitRegister) > max){
              mw._on.userIteractionInitRegister = time;
              mw.trigger('UserInteraction');
          }
      });
  }
};

for(var x in mw._on) mw.on[x] = mw._on[x];



mw.hashHistory = [window.location.hash]

mw.prevHash = function(){
  var prev = mw.hashHistory[mw.hashHistory.length - 2];
  return prev !== undefined ? prev : '';
};



$(window).on("hashchange load", function(event){
    if(event.type === 'load'){
        mw._on.userIteractionInit();
    }

    mw.on.hashParamEventInit();

   var hash =  location.hash;

   var isMWHash = hash.replace(/\#/g, '').indexOf('mw@') === 0;
   if (isMWHash) {
       var MWHash = hash.replace(/\#/g, '').replace('mw@', '');
       var el = document.getElementById(MWHash);
       if(el) {
           mw.tools.scrollTo(el);
       }
   }
   if(hash.contains("showpostscat")){
      mw.$("html").addClass("showpostscat");
   }
   else{
      mw.$("html").removeClass("showpostscat");
   }


   if (event.type === 'hashchange') {
     mw.hashHistory.push(location.hash);
     var size = mw.hashHistory.length;
     var changes = mw.url.whichHashParamsHasBeenRemoved(mw.hashHistory[size-1], mw.hashHistory[size-2]), l=changes.length, i=0;
     if (l>0) {
       for( ; i < l; i++ ){
          mw.on.hashParam(changes[i], "", true, true);
       }
     }
   }
});


mw.event = {
    windowLeave: function(c) {
      document.documentElement.addEventListener('mouseout', function(e) {
          if (!e.relatedTarget && !e.toElement && c) {
              c.call(document.body, e);
          }
      });
    },
    cancel:function(e, prevent){
    prevent === true ? e.preventDefault() : '';
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
    },
    key:function(e,key){
        return (e.keyCode === parseFloat(key));
    },
    page: function (e) {
      e = e.originalEvent || e;
      if (e.type.indexOf('touch') !== 0) {
        return {
            x: e.pageX,
            y: e.pageY
        };
      } else {
          return {
              x: e.changedTouches[0].pageX,
              y: e.changedTouches[0].pageY
          };
      }
    },
    targetIsField: function(e) {
        e = e.originalEvent || e;
        var t = e.target;
        return t.nodeName === 'INPUT' ||
            t.nodeName === 'textarea' ||
            t.nodeName === 'select';
    },
    get: function(e) {
        return e.originalEvent || e;
    },
    keyCode: function(e) {
        e = mw.event.get(e);
        return e.keyCode || e.which;
    },
    isKeyCode: function(e, code){
        return this.keyCode(e) === code;
    },
    is: {
      comma: function (e) {
          e = mw.event.get(e);
          return e.keyCode === 188;
              },
      enter: function (e) {
        e = mw.event.get(e);
        return e.key === "Enter" || mw.event.isKeyCode(e, 13);
      },
      escape: function (e) {
          e = mw.event.get(e);
          return e.key === "Escape" || mw.event.isKeyCode(e, 27);
      },
      backSpace : function (e) {
        e = mw.event.get(e);
        return e.key === "Backspace" || mw.event.isKeyCode(e, 8);
      },
      delete: function (e) {
          e = mw.event.get(e);
          return e.key === "Delete" || mw.event.isKeyCode(e, 46);
      }
    }
};



mw.on('ComponentsLaunch', function () {
    mw.components._init();
});

mw.on('mwDialogShow', function () {
    setTimeout(function () {
        mw.components._init();
    }, 110);
});









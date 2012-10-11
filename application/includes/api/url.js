// URL Strings - Manipulations

json2url = function(obj){var t=[];for(var x in obj)t.push(x+"="+encodeURIComponent(obj[x]));return t.join("&").replace(/undefined/g, 'false')};

mw.url = {
    getDomain:function(url){
      return url.match(/:\/\/(www\.)?(.[^/:]+)/)[2];
    },
    removeHash:function(url){
        return url.replace( /#.*/, "");
    },
    getHash:function(url){
      return url.indexOf('#')!=-1 ? url.substring(url.indexOf('#'), url.length) : "";
    },
    strip:function(url){
      return url.replace(/#[^#]*$/, "").replace(/\?[^\?]*$/, "")
    },
    getUrlParams:function(url){
        var url = mw.url.removeHash(url);
        if(url.contains('?')){
          var arr = url.slice(url.indexOf('?') + 1).split('&');
          var obj = {}
          for(var i=0;i<arr.length;i++){
            var p_arr = arr[i].split('=');
            obj[p_arr[0]] = p_arr[1];
          }
          return obj;
        }
        else{return {} }
    },
    set_param:function(param, value, url){
        var url = url || window.location.href;
        var hash = mw.url.getHash(url);
        var params = mw.url.getUrlParams(url);
        params[param] = value;
        var params_string = json2url(params);
        var url = mw.url.strip(url);
        return decodeURIComponent (url + "?" + params_string + hash);
    },
    remove_param:function(url, param){
        var hash = mw.url.getHash(url);
        var params = mw.url.getUrlParams(url);
        delete params[param];
        var params_string = json2url(params);
        var url = mw.url.strip(url);
        return decodeURIComponent (url + "?" + params_string + hash);
    },
    getHashParams:function(hash){
        if(hash=='' || hash=='#' || hash =='#?'){
          return {}
        }
        else{
          var hash = hash.replace(/#/g, "");
          var hash = hash.replace(/\?/g, "");
          var arr = hash.split('&');
          var obj = {}
          for(var i=0;i<arr.length;i++){
            var p_arr = arr[i].split('=');
            obj[p_arr[0]] = p_arr[1];
          }
          return obj;
        }
    },
    setHashParam:function(param, value, hash){
      var hash = hash || window.location.hash;
      var obj = mw.url.getHashParams(hash);
      obj[param] = value;
      return "?"+json2url(obj);
    },
    windowHashParam:function(a,b){
      window.location.hash = mw.url.setHashParam(a,b);
    }
}

mw._hashrec = {};

mw._hashparams = [];
mw._hashparam_funcs = [];

mw.on.hashParam = function(param, callback, trigger){
    if(trigger==true){
          var index = mw._hashparams.indexOf(param);
          if(index != -1){
            var hash = window.location.hash;
            var params = mw.url.getHashParams(hash);
            mw._hashparam_funcs[index].call(params[param]);
          }
    }
    else{
        mw._hashparams.push(param);
        mw._hashparam_funcs.push(callback);
    }
}

$(window).bind("hashchange load", function(){
  var hash = window.location.hash;
  var params = mw.url.getHashParams(hash);
  if(hash=='' || hash=='#' || hash =='#?'){
    for(var i = 0; i<mw._hashparams.length; i++){
        mw.on.hashParam(mw._hashparams[i], "", true);
    }
  }
  else{
    for(var x in params){
        if(params[x] !== mw._hashrec[x] || mw._hashrec[x]===undefined){
            mw.on.hashParam(x, "", true);
        }
    }
  }
  mw._hashrec = params;
});



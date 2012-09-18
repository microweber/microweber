// URL Strings - Manipulations

json2url = function(obj){var t=[];for(var x in obj)t.push(x+"="+encodeURIComponent(obj[x]));return t.join("&")};

mw.url = {
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
    set_param:function(url, param, value){
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
}
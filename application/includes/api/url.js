// URL Strings - Manipulations

// Do not change the encoding of this file

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
          var obj = {}, i=0, len = arr.length;
          for( ; i<len; i++){
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
          var obj = {}, i=0, len = arr.length;
          for( ; i<len; i++){
            var p_arr = arr[i].split('=');
            obj[p_arr[0]] = p_arr[1];
          }
          return obj;
        }
    },
    setHashParam:function(param, value, hash){
      var hash = hash || mw.hash();
      var obj = mw.url.getHashParams(hash);
      obj[param] = value;
      return "?"+ decodeURIComponent(json2url(obj));
    },
    windowHashParam:function(a,b){
      mw.hash(mw.url.setHashParam(a,b));
    },
    deleteHashParam:function(hash, param){
        var params = mw.url.getHashParams(hash);
        delete params[param];
        var params_string = decodeURIComponent("?"+json2url(params));
        return params_string;
    },
    windowDeleteHashParam:function(param){
       mw.hash(mw.url.deleteHashParam(window.location.hash, param));
    }
}

mw.slug = {
  normalize:function(string){
    return string.replace(/[`~!@#$%^&№€§*()\=?'"<>\{\}\[\]\\\/]/g, '');
  },
  removeSpecials:function(string){
    var string = mw.slug.normalize(string);
    var special = 'àáäãâèéëêìíïîòóöôõùúüûñç·/_,:;',
        normal =  'aaaaaeeeeiiiiooooouuuunc-------',
        len = special.length,
        i = 0;
    for ( ; i<len; i++) {
       var bad = special[i];
       var good = normal[i];
       var string = string.replace(new RegExp(bad, 'g'), good);
    }
    return string;
  },
  create:function(string){
    var string = mw.slug.removeSpecials(string);
    return string.trim().toLowerCase().replace(/[-\s]+/g, '-');
  },
  toggleEdit:function(){
    var edit = mw.$(".edit-post-slug");
    var view = mw.$(".view-post-slug");
    $([edit, view]).toggleClass('active');

    if(view.hasClass("active")){
     view.html(edit.val());
    }
    else{
       edit.focus();
       mw.slug.fieldAutoWidthGrow(edit[0]);
    }
  },
  fieldAutoWidthGrow:function(field){
    var element = mw.$(".view-post-slug");
    element[0].innerHTML = field.value;
    $(field).width(element.width() + 10)
  },
  setVal:function(el){
    var val = mw.slug.create(el.value)
    el.value=val;
    mw.$(".view-post-slug").html(val)
  }
}

mw.walker = function(context, callback){   //todo
  var context = mw.is.obj(context) ? context : mwd.body;
  var callback = mw.is.func(context) ? context :  callback;
  var walker = document.createTreeWalker(context, NodeFilter.SHOW_ELEMENT, null, false);
  while (walker.nextNode()){
    callback.call(walker.currentNode);
  }
}





























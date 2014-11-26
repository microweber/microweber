mw.cacheURL = function(url, data){
    if(!url) return false;
    if(!data){
      return mw.storage.get(url);
    }
    else{
       mw.storage.set(url, data);
    }
}


mw.ajax = function(url, data, callback){
    if(!url || !data) return false;
    data.url = url;
    var cache = mw.cacheURL(url);
    if(!cache){
        var xhr = jQuery.ajax(data);
        xhr.success(function(da){
            mw.cacheURL(url,da.toString())
          callback.call(da.toString());
        });
    }
    else{
        callback.call(cache.toString());
    }
}



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



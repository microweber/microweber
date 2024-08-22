mw.reload_module = function(module, callback) {





    if(module.constructor === [].constructor){
        var l = module.length, i=0, w = 1;
        for( ; i<l; i++){
            mw.reload_module(module[i], function(){
                w++;
                if(w === l && typeof callback === 'function'){
                    callback.call();
                }
                $( this ).trigger('ModuleReload')
            });
        }
        return false;
    }
    var done = callback || function(){};
    if (typeof module !== 'undefined') {
        if (typeof module === 'object') {

            mw._({
                selector: module,
                done:done
            });
        } else {
            var module_name = module.toString();
            var refresh_modules_explode = module_name.split(",");
            for (var i = 0; i < refresh_modules_explode.length; i++) {
                var module = refresh_modules_explode[i];
                if (typeof module != 'undefined') {
                    module = module.replace(/##/g, '#');
                    var m = mw.$(".module[data-type='" + module + "']");
                    if (m.length === 0) {
                        try {   m = $(module); }  catch(e) {};
                    }

                    (function(callback){
                        var count = 0;
                        for (var i=0;i<m.length;i++){
                            mw.reload_module(m[i], function(){
                                count++;
                                if(count === m.length && typeof callback === 'function'){
                                    callback.call();
                                }
                                $( document ).trigger('ModuleReload')
                            })
                        }
                    })(callback)



                }
            }
        }
    }
}
mw.Xreload_module = function(module, callback) {
    console.log(1, 'mw.reload_module')

    if(Array.isArray(module)){
        var l = module.length, i=0, w = 1;
        for( ; i<l; i++){
          mw.reload_module(module[i], function(){
            w++;
            if(w === l && typeof callback === 'function'){
              callback.call();
            }
            $( this ).trigger('ModuleReload')

              //
              // if(self !== top && top && top.mw) {
              //     if (module[i] && module[i].id) {
              //         mw.top().app.dispatch('onModuleReloaded', module[i].id);
              //     }
              // }

          });
        }
        return false;
    }
    var currId = false;
    var doc = false;

    var done = callback || function(){};
    if (typeof module !== 'undefined') {
      if (typeof module === 'object') {

          if(mw.top().app && mw.top().app.liveEdit && mw.top().app.liveEdit.handles.get('module')) {
              var curr = mw.top().app.liveEdit.handles.get('module').getTarget();
              if (curr && curr === module) {
                  currId = curr.id;
                  doc = curr.ownerDocument;
              }
          }

        var xhr = mw._({
          selector: module,
          done: done
        });



        if(xhr) {
            xhr.success(function(){
                if (mw.top().app && mw.top().app.liveEdit && mw.top().app.liveEdit.handles.get('module')) {
                    if (doc) {
                        var newNode = doc.getElementById(currId);
                        if (newNode) {
                            mw.top().app.liveEdit.handles.get('module').set(newNode);
                            mw.top().app.liveEdit.handles.get('module').position(newNode);
                        }
                    }
                }
            });
        }

      } else {
        var module_name = module.toString();
        var refresh_modules_explode = module_name.split(",");
        for (var i = 0; i < refresh_modules_explode.length; i++) {
          module = refresh_modules_explode[i];
          if (typeof module != 'undefined') {
		    module = module.replace(/##/g, '#');
            var m = mw.$(".module[data-type='" + module + "']");
            if (m.length === 0) {
                try { m = $(module); }  catch(e) {};
            }



            if( !m.length && typeof callback === 'function'){
                callback.call();
            }

              (function(callback){
                  var count = 0;
                  for (var i=0;i<m.length;i++){
                      mw.reload_module(m[i], function(){
                          count++;
                          if(count === m.length && typeof callback === 'function'){
                              callback.call();
                          }
                          $( document ).trigger('ModuleReload')
                      })
                  }
              })(callback)


          }
        }
      }
    } else {
        if( typeof callback === 'function'){
            callback.call();
        }
    }

  }

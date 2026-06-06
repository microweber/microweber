mw.reload_module = function(module, callback) {




    if(module.constructor === [].constructor){
        var l = module.length, i=0, w = 1;
        for( ; i<l; i++){
            mw.reload_module(module[i], function(){
                w++;
                if(w === l && typeof callback === 'function'){
                    callback.call();
                }
                $( this ).trigger('ModuleReload');
                // task-2026-06-06-cartdispatch — mw.top().app is undefined on the
                // public storefront (app only exists in admin/live-edit); guard
                // so add-to-cart's reload_modules doesn't throw.
                if (mw.top() && mw.top().app && typeof mw.top().app.dispatch === 'function') {
                    mw.top().app.dispatch('moduleReloaded', module[i]);
                }
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
                                $( document ).trigger('ModuleReload');
                                // task-2026-06-06-cartdispatch — see note above.
                                if (mw.top() && mw.top().app && typeof mw.top().app.dispatch === 'function') {
                                    mw.top().app.dispatch('moduleReloaded', m[i]);
                                }
                            })
                        }
                    })(callback)



                }
            }
        }
    }
}

mw.storage = {
    init: function () {

        try {
            if (window.location.href.indexOf('data:') === 0 || !('localStorage' in mww) || /* IE Security configurations */ typeof mww['localStorage'] === 'undefined') return false;
            var lsmw = localStorage.getItem("mw");
            if (typeof lsmw === 'undefined' || lsmw === null) {
                lsmw = localStorage.setItem("mw", "{}");
            }
            this.change("INIT");
            return lsmw;

        } catch (error) {
            console.log(error);
        }


    },
    set: function (key, val) {
        try {
            if (!('localStorage' in mww)) return false;
            var curr = JSON.parse(localStorage.getItem("mw"));
            curr[key] = val;
            var a = localStorage.setItem("mw", JSON.stringify(curr));
            mw.storage.change("CALL", key, val);
            return a;
        } catch (error) {
            console.log(error);
        }

    },
    get: function (key) {
        try {
            if (!('localStorage' in mww)) return false;
            var curr = JSON.parse(localStorage.getItem("mw"));
            return curr[key];
        } catch (error) {
            console.log(error);
        }
    },
    _keys: {},
    change: function (key, callback, other) {
        if (!('localStorage' in mww)) return false;
        if (key === 'INIT' && 'addEventListener' in document) {
            addEventListener('storage', function (e) {
                if (e.key === 'mw') {
                    if (e.newValue === null) {
                        return;
                    }

                    if (e.oldValue === null) {
                        return;
                    }

                    var _new = JSON.parse(e.newValue || {});
                    var _old = JSON.parse(e.oldValue || {});
                    var diff = mw.tools.getDiff(_new, _old);
                    for (var t in diff) {
                        if (t in mw.storage._keys) {
                            var i = 0, l = mw.storage._keys[t].length;
                            for (; i < l; i++) {
                                mw.storage._keys[t][i].call(diff[t]);
                            }
                        }
                    }
                }
            }, false);
        }
        else if (key === 'CALL') {
            if (!document.isHidden() && typeof mw.storage._keys[callback] !== 'undefined') {
                var i = 0, l = mw.storage._keys[callback].length;
                for (; i < l; i++) {
                    mw.storage._keys[callback][i].call(other);
                }
            }
        }
        else {
            if (key in mw.storage._keys) {
                mw.storage._keys[key].push(callback);
            }
            else {
                mw.storage._keys[key] = [callback];
            }
        }
    }
};
mw.storage.init();

import "./options.js";

mw.pauseSave = false;

mw.askusertostay = false;

if (window.top === window){
    window.onbeforeunload = function() {
        if (mw.askusertostay) {
            mw.notification.warning(mw.lang('You have unsaved changes'));
            return mw.lang('You have unsaved changes');
        }
    };
}


window.mwd = document;
window.mww  = window;

window.mwhead = document.head || document.getElementsByTagName('head')[0];

mw.doc = document;
mw.win = window;
mw.head = mwhead;

mw.loaded = false;

mw._random = new Date().getTime();

mw.random = function() {
    return mw._random++;
};

mw.id = function(prefix) {
    prefix = prefix || 'mw-';
    return prefix + mw.random();
};

mw.onLive = function(callback) {
    if (typeof mw.settings.liveEdit === 'boolean' && mw.settings.liveEdit) {
        callback.call(this)
    }
};
mw.onAdmin = function(callback) {
    if ( window['mwAdmin'] ) {
        callback.call(this);
    }
};


mw.target = {};

mw.log = function() {
    if (mw.settings.debug) {
        top.console.log(...arguments);
    }
};


mw.$ = function(selector, context) {
    if(typeof selector === 'object' || (typeof selector === 'string' && selector.indexOf('<') !== -1)){ return jQuery(selector); }
    context = context || mwd;
    if (typeof document.querySelector !== 'undefined') {
        if (typeof selector === 'string') {
            try {
                return jQuery(context.querySelectorAll(selector));
            } catch (e) {
                return jQuery(selector, context);
            }
        } else {
            return jQuery(selector, context);
        }
    } else {
        return jQuery(selector, context);
    }
};


mw.parent = function(){
    if(window === top){
        return window.mw;
    }
    if(mw.tools.canAccessWindow(parent) && parent.mw){
        return parent.mw;
    }
    return window.mw;
};

mw.top = function() {
    if(!!mw.__top){
        return mw.__top;
    }
    var getLastParent = function() {
        var result = window;
        var curr = window;
        while (curr && mw.tools.canAccessWindow(curr) && (curr.mw || curr.parent.mw)){
            result = curr;
            curr = curr.parent;
        }
        mw.__top = curr.mw;
        return result.mw;
    };
    if(window === top){
        mw.__top = window.mw;
        return window.mw;
    } else {
        if(mw.tools.canAccessWindow(top) && top.mw){
            mw.__top = top.mw;
            return top.mw;
        } else{
            if(window.top !== window.parent){
                return getLastParent();
            }
            else{
                mw.__top = window.mw;
                return window.mw;
            }
        }
    }
};

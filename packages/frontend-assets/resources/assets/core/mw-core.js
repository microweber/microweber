// import "./container.js";



if(window.mw) {
    console.log('"mw" already defined');
}



if(!window.mw) {
    window.mw = {};
}

/*
 * AI-55 / TICKET-CC (cycle-79 2026-05-08): mw.app.dispatch() guard
 * for public-site execution.
 *
 * `mw.app` is the live-edit event bus; it's only constructed by the
 * admin Live-Edit bundle. Public-site scripts that accidentally
 * inherit a snippet calling `mw.app.dispatch('something')` would
 * throw "Cannot read properties of undefined" and break the entire
 * public page.
 *
 * The guard installs a minimal no-op surface for the public site:
 *   - mw.app is at least an object
 *   - mw.app.dispatch / on / off / off_all exist as no-ops
 *
 * Admin Live-Edit overrides this surface with its real event bus
 * during boot — the no-op shim is only present until then. Any
 * dispatch fired BEFORE the real bus loads is silently swallowed,
 * which is the right semantics for the public site (it's not
 * listening anyway).
 *
 * The guard is idempotent: if mw.app is already populated (admin
 * page mid-boot), or if the real bus has overwritten the shim,
 * we leave it alone. This keeps live-edit semantics unchanged on
 * admin pages.
 */
if (typeof mw.app !== 'object' || mw.app === null) {
    mw.app = {};
}
if (typeof mw.app.dispatch !== 'function') {
    mw.app.dispatch = function () { /* no-op: real bus not loaded */ };
}
if (typeof mw.app.on !== 'function') {
    mw.app.on = function () { /* no-op: real bus not loaded */ };
}
if (typeof mw.app.off !== 'function') {
    mw.app.off = function () { /* no-op: real bus not loaded */ };
}
if (typeof mw.app.off_all !== 'function') {
    mw.app.off_all = function () { /* no-op: real bus not loaded */ };
}


mw.required = [] ;
mw.require = function(url, inHead, key, defered) {
    if(!url) return;
    var defer;
    if(defered) {
        defer = ' defer ';
    } else {
        defer = '   '
    }
    if(typeof inHead === 'boolean' || typeof inHead === 'undefined'){
        inHead = inHead || false;
    }
    var keyString;
    if(typeof inHead === 'string'){
        keyString = ''+inHead;
        inHead = key || false;
    }
    if(typeof key === 'string'){
        keyString = key;
    }
    var toPush = url, urlModified = false;
    if (!!keyString) {
        toPush = keyString;
        urlModified = true
    }
    var t = url.split('.').pop();
    url = url.includes('//') ? url : (t !== "css" ? mw.settings.includes_url + "api/" + url  :  mw.settings.includes_url + "css/" + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {

    mw.required.push(toPush);
    var _mwv = (typeof mw.version === 'string' && mw.version) ? mw.version : '';
    url = url.includes("?") ?  url + '&mwv=' + _mwv : url + "?mwv=" + _mwv;
    if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
        return
    }

    var cssRel = " rel='stylesheet' ";




    var string = t !== "css" ? "<script "+defer+"  src='" + url + "'></script>" : "<link "+cssRel+" href='" + url + "' />";

        if(typeof window.$?.fn === 'object'){
            $(document.head).append(string);
        }
        else{
            var el;
            if( t !== "css")  {
                el = document.createElement('script');
                el.src = url;
                el.defer = !!defer;
                el.setAttribute('type', 'text/javascript');
                document.head.appendChild(el);
            }
            else{

                el = document.createElement('link');
                if(defered) {
                    el.as='style';
                    el.rel='preload';
                    el.addEventListener('load', e => el.rel='stylesheet');
                } else {
                    el.rel='stylesheet';
                }


                el.href = url;
                document.head.appendChild(el);
            }
        }

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


mw._random = new Date().getTime();

mw.random = function() {
    return mw._random++;
};

mw.id = function(prefix) {
    prefix = prefix || 'mw-';
    return prefix + mw.random();
};



// mw = mw || {};
//
// mwd = document;
//
// mww = window;
//
// document.head = document.head || document.getElementsByTagName('head')[0];
//
// mw.doc = mwd;
// mw.win = window;

//
// import "./mw-require.js";

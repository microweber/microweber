mw.required = [] ;
mw.require = function(url, inHead, key, defer) {
    if(!url) return;
    if(defer) {
        defer = ' defer ';
    } else {
        defer = ''
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
        urlModified = true;
    }
    var t = url.split('.').pop();
    var scope =  mw.settings.modules_url + '/microweber';
    if(!url.contains('/')) {
        return;
    }
    url = url.contains('//') ? url : (t !== 'css' ? ( scope + '/api/' + url)  :  scope + '/css/' + url);
    if(!urlModified) toPush = url;
    if (!~mw.required.indexOf(toPush)) {
        mw.required.push(toPush);
        url = url.contains("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
        if(document.querySelector('link[href="'+url+'"],script[src="'+url+'"]') !== null){
            return;
        }
        var string = t !== "css" ? "<script type='text/javascript' "+defer+"  src='" + url + "'></script>" : "<link rel='stylesheet' type='text/css' href='" + url + "' />";
        if(typeof $.fn === 'object'){
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
                el.rel='stylesheet';
                el.type='text/css';
                el.href = url;
                document.head.appendChild(el);
            }
        }

    }
};

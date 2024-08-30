mw.cookie = {
    get: function (name) {
        var cookies = document.cookie.split(";"), i = 0, l = cookies.length;
        for (; i < l; i++) {
            var x = cookies[i].substr(0, cookies[i].indexOf("="));
            var y = cookies[i].substr(cookies[i].indexOf("=") + 1);
            x = x.replace(/^\s+|\s+$/g, "");
            if (x === name) {
                return unescape(y);
            }
        }
    },
    delete: function (name) {
        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    },
    set: function (name, value, expires, path, domain, secure) {
        var now = new Date();
        expires = expires || 365;
        now.setTime(now.getTime());
        if (expires) {
            expires = expires * 1000 * 60 * 60 * 24;
        }
        var expires_date = new Date(now.getTime() + (expires));
        document.cookie = name + "=" + escape(value) + ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) + ( ( path ) ? ";path=" + path : ";path=/" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
    },
    setEncoded: function (name, value, expires, path, domain, secure) {
        // value = encodeURIComponent(value);
        // value = escape(value);
        // value = mw.tools.base64.encode( unescape( encodeURIComponent( value ) ) )
        value = mw.tools.base64.encode(value);
        return this.set(name, value, expires, path, domain, secure);
    },
    getEncoded: function (name) {
        var value = this.get(name);
        // value = decodeURIComponent(value);
        // value = unescape(value);
        // value = decodeURIComponent( escape( mw.tools.base64.decode( value ) ) )
        value = mw.tools.base64.decode(value);
        return value;
    },
    ui: function (a, b) {
        var mwui = mw.cookie.getEncoded("mwui");
        try {
            mwui = (!mwui || mwui === '') ? {} : $.parseJSON(mwui);
        }
        catch (e) {
            return false;
        }
        if (typeof a === 'undefined') {
            return mwui;
        }
        if (typeof b === 'undefined') {
            return mwui[a] !== undefined ? mwui[a] : "";
        }
        else {
            mwui[a] = b;
            var tostring = JSON.stringify(mwui);
            mw.cookie.setEncoded("mwui", tostring, false, "/");
            if (typeof mw.cookie.uievents[a] !== 'undefined') {
                var funcs = mw.cookie.uievents[a], l = funcs.length, i = 0;
                for (; i < l; i++) {
                    mw.cookie.uievents[a][i].call(b.toString());
                }
            }
        }
    },
    uievents: {},
    changeInterval: null,
    uiCurr: null,
    onchange: function (name, func) {
        if (typeof mw.cookie.uievents[name] === 'undefined') {
            mw.cookie.uievents[name] = [func];
        }
        else {
            mw.cookie.uievents[name].push(func);
        }
    }
};

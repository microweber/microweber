

mw._intervals = {};
mw.interval = function(key, func){
    if(!key || !func || !!mw._intervals[key]) return;
    mw._intervals[key] = func;
};
mw.removeInterval = function(key){
    delete mw._intervals[key];
};
setInterval(function(){
    for(var i in mw._intervals){
        mw._intervals[i].call();
    }
}, 99);





mw.controllers = {};
mw.external_tool = function (url) {
    return !url.includes("/") ? mw.settings.site_url + "editor_tools/" + url : url;
};




let _mwdochidden;
if (typeof document.hidden !== "undefined") {
    _mwdochidden = "hidden";
} else if (typeof document.mozHidden !== "undefined") {
    _mwdochidden = "mozHidden";
} else if (typeof document.msHidden !== "undefined") {
    _mwdochidden = "msHidden";
} else if (typeof document.webkitHidden !== "undefined") {
    _mwdochidden = "webkitHidden";
}
document.isHidden = function () {
    if (typeof _mwdochidden !== 'undefined') {
        return document[_mwdochidden];
    }
    else {
        return !document.hasFocus();
    }
};


mw.postMsg = function (w, obj) {
    w.postMessage(JSON.stringify(obj), window.location.href);
};

mw.uploader = function (o) {

    mw.require("files.js");

    var uploader = mw.files.uploader(o);

    return uploader;
};

mw.fileWindow = function (config) {
    config = config || {};
    config.mode = config.mode || 'dialog'; // 'inline' | 'dialog'
    var q = {
        types: config.types,
        title: config.title
    };


    url = mw.settings.site_url + 'editor_tools/rte_image_editor?' + $.param(q) + '#fileWindow';
    var frameWindow;
    var toreturn = {
        dialog: null,
        root: null,
        iframe: null
    };
    if (config.mode === 'dialog') {
        var modal = mw/*.top()*/.dialogIframe({
            url: url,
            name: "mw_rte_image",
            width: 530,
            height: 'auto',
            autoHeight: true,
            //template: 'mw_modal_basic',
            overlay: true,
            title: mw.lang('Select image')
        });
        var frame = mw.$('iframe', modal.main);
        frameWindow = frame[0].contentWindow;
        toreturn.dialog = modal;
        toreturn.root = frame.parent()[0];
        toreturn.iframe = frame[0];
        frameWindow.onload = function () {
            frameWindow.$('body').on('Result', function (e, url, m) {
                 if (config.change) {
                    config.change.call(undefined, url);
                    modal.remove();
                }
            });
            $(modal).on('Result', function (e, url, m) {
                if (config.change) {
                    config.change.call(undefined, url);
                    modal.remove();
                }
            });
        };
    } else if (config.mode === 'inline') {
        var fr = document.createElement('iframe');
        fr.src = url;
        fr.frameBorder = 0;
        fr.className = 'mw-file-window-frame';
        toreturn.iframe = fr;
        mw.tools.iframeAutoHeight(fr);
        if (config.element) {
            var $el = $(config.element);
            if($el.length) {
                toreturn.root = $el[0];
            }
            $el.append(fr);
        }
        fr.onload = function () {
            this.contentWindow.$('body').on('change', function (e, url, m) {
                if (config.change) {
                    config.change.call(undefined, url);
                }
            });
        };
    }


    return toreturn;
};




mw.accordion = function (el, callback) {
    return mw.tools.accordion(mw.$(el)[0], callback);
};


//libs
//import "../../../../frontend-assets-libs/resources/dist/xss/xss.js";


// import "jquery";

//import jQuery from 'jquery';
//const jQuery  = (await import("jquery/dist/jquery.js")).default;

//import "../core/mw-require.js";




window.$ = jQuery;
window.jQuery = jQuery;
globalThis.$ = jQuery;
globalThis.jQuery = jQuery;

//await import("jquery-ui/dist/jquery-ui.js");


import TomSelect  from "tom-select";

// TinyMCE removed (task-2026-09-27) — richtext-editor.js deleted.




//
globalThis.TomSelect = TomSelect;
window.TomSelect = TomSelect;


import * as AColorPicker from "a-color-picker";


window.AColorPicker = AColorPicker;


// TinyMCE removed. mw.richTextEditor now builds a Microweber MWEditor (div mode)
// on the given target, lazy-loading the editor lib from frontend-assets-libs.
// Same call shape (options.target / options.selector).
mw.richTextEditor = function (options) {
    options = options || {};
    var target = options.target || options.selector;
    var base = '/vendor/microweber-packages/frontend-assets-libs/';
    var build = function () {
        if (!target || typeof mw.Editor !== 'function') return null;
        var ed = mw.Editor(Object.assign({ mode: 'div', selector: target, skin: 'le2' }, options));
        if (ed && ed.wrapper && target.parentNode) {
            target.style.display = 'none';
            target.parentNode.insertBefore(ed.wrapper, target.nextSibling);
        }
        return ed;
    };
    if (typeof mw.Editor === 'function') { return build(); }
    ['api/editor/editor.css', 'api/editor/area-styles.css'].forEach(function (h) {
        if (!document.querySelector('link[data-mw-editor-css="' + h + '"]')) {
            var l = document.createElement('link'); l.rel = 'stylesheet'; l.href = base + h;
            l.setAttribute('data-mw-editor-css', h); document.head.appendChild(l);
        }
    });
    if (!document.querySelector('script[data-mw-editor-lib]')) {
        var s = document.createElement('script'); s.src = base + 'api/editor.js';
        s.setAttribute('data-mw-editor-lib', '1'); document.head.appendChild(s);
    }
    var tries = 0;
    (function w() { if (typeof mw.Editor === 'function') { build(); return; } if (tries++ > 100) { return; } setTimeout(w, 50); })();
    return null;
};





// import "jquery-ui/dist/jquery-ui.js";


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN':  (document.querySelector('meta[name="csrf-token"]').content || '').trim()
    }
});


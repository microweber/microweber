import {MWUniversalContainer} from "../core/container.js";



import * as AColorPicker from "a-color-picker";


window.AColorPicker = AColorPicker;





import $ from "jquery";
window.$ = $;
window.jQuery = $;
globalThis.$ = $;
globalThis.jQuery = $;


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN':  (document.querySelector('meta[name="csrf-token"]').content || '').trim()
    }
});

mw.admin = new MWUniversalContainer();
mw.app = mw.admin;
mw.widget = {};

//libs
import "xss";


// import "jquery";

import jQuery from 'jquery';
//const jQuery  = (await import("jquery/dist/jquery.js")).default;





window.$ = jQuery;
window.jQuery = jQuery;
globalThis.$ = jQuery;
globalThis.jQuery = jQuery;

await import("jquery-ui/dist/jquery-ui.js");


import TomSelect  from "tom-select";

import {RichTextEditor} from "../components/richtext-editor.js";



globalThis.TomSelect = TomSelect;
window.TomSelect = TomSelect;


import * as AColorPicker from "a-color-picker";


window.AColorPicker = AColorPicker;


mw.richTextEditor = options => new RichTextEditor(options);





// import "jquery-ui/dist/jquery-ui.js";


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN':  (document.querySelector('meta[name="csrf-token"]').content || '').trim()
    }
});


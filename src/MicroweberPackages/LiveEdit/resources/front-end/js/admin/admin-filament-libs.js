//libs
import "xss";

import TomSelect  from "tom-select";

import {RichTextEditor} from "../components/richtext-editor.js";



globalThis.TomSelect = TomSelect;
window.TomSelect = TomSelect;


import * as AColorPicker from "a-color-picker";


window.AColorPicker = AColorPicker;


mw.richTextEditor = RichTextEditor;


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

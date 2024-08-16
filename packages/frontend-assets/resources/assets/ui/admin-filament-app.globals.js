import {MWUniversalContainer} from "../api-core/services/containers/container.js";
import "../../../../../../userfiles/modules/microweber/api/tools/spinner.js";
import "../../../../../../userfiles/modules/microweber/js/tools/icon-resolver.js";

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

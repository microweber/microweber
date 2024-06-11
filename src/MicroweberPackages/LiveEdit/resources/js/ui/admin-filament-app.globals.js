import {MWUniversalContainer} from "../api-core/services/containers/container.js";
import {Spinner} from "../../../../../../userfiles/modules/microweber/api/tools/spinner.js";
import "../../../../../../userfiles/modules/microweber/js/tools/icon-resolver.js";


import $ from "jquery";
window.$ = $;
window.jQuery = $;
globalThis.$ = $;
globalThis.jQuery = $;
console.log(jQuery);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN':  (document.querySelector('meta[name="csrf-token"]').content || '').trim()
    }
});

mw.admin = new MWUniversalContainer();
mw.app = mw.admin;
mw.widget = {};

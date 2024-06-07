
import $ from "jquery";
window.$ = $;
window.jQuery = $;
globalThis.$ = $;
globalThis.jQuery = $;

import "../api-core/core/core/@core.js";

mw.settings = {
    site_url: 'http://127.0.0.2/'
}

import {MWUniversalContainer} from "../api-core/services/containers/container.js";
import {AdminTools} from "./admin-tools.service.js";
import {AdminFilament} from "./admin-filament/admin-filament.js";
import "../../../../../../userfiles/modules/microweber/api/icon_selector.js";
import {IconPicker} from "../../../../../../src/MicroweberPackages/LiveEdit/resources/js/api-core/services/services/icon-picker.js";




import "xss";
import "../../../../../../src/MicroweberPackages/LiveEdit/resources/js/api-core/core/core/i18n.js";

import "../../../../../../userfiles/modules/microweber/api/tools/element.js";
import "../../../../../../userfiles/modules/microweber/api/tools/tabs.js";
import "../../../../../../userfiles/modules/microweber/api/tools/dialog.js";

import "../../../../../../userfiles/modules/microweber/api/tools/common-extend.js";
import "../../../../../../userfiles/modules/microweber/api/autocomplete.js";
import "../../../../../../userfiles/modules/microweber/api/form-controls.js";
import "../../../../../../userfiles/modules/microweber/api/uploader.js";
import "../../../../../../userfiles/modules/microweber/api/filemanager.js";

import "../../../../../../userfiles/modules/microweber/api/filepicker.js";
import "../../../../../../userfiles/modules/microweber/api/link-editor.js";


mw.admin = new MWUniversalContainer();
mw.app = mw.admin;

mw.tools = new AdminTools(mw.app);


mw.app.register('iconPicker', IconPicker);


mw.admin.filament = new AdminFilament();




mw.admin.dispatch('init');
mw.admin.dispatch('ready');



console.log('admin-filament-app.js');

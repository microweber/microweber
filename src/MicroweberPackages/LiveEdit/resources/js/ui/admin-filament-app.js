





import "../api-core/core/core/@core.js";
import "./admin-filament-app.globals.js";




import {AdminTools} from "./admin-tools.service.js";
import {AdminFilament} from "./admin-filament/admin-filament.js";
import "../../../../../../userfiles/modules/microweber/api/icon_selector.js";




//libs
import "xss";

import TomSelect  from "tom-select";




globalThis.TomSelect = TomSelect;
window.TomSelect = TomSelect;





//core
import "../../../../../../src/MicroweberPackages/LiveEdit/resources/js/api-core/core/core/i18n.js";

import "../../../../../../userfiles/modules/microweber/js/tools/objects.js";

import "../../../../../../userfiles/modules/microweber/js/components/tree.js";
import "../../../../../../userfiles/modules/microweber/api/tags.js";
import "../../../../../../userfiles/modules/microweber/api/tools/storage.js";
import "../../../../../../userfiles/modules/microweber/api/tools/element.js";
import "../../../../../../userfiles/modules/microweber/api/tools/cookie.js";
import "../../../../../../userfiles/modules/microweber/api/tools/common-extend.js";


//components
import "../../../../../../userfiles/modules/microweber/api/tools/tabs.js";
import "../../../../../../userfiles/modules/microweber/api/tools/dialog.js";

import "../../../../../../userfiles/modules/microweber/api/autocomplete.js";
import "../../../../../../userfiles/modules/microweber/api/form-controls.js";
import "../../../../../../userfiles/modules/microweber/api/uploader.js";
import "../../../../../../userfiles/modules/microweber/api/filemanager.js";

import "../../../../../../userfiles/modules/microweber/api/filepicker.js";
import "../../../../../../userfiles/modules/microweber/api/link-editor.js";
import "../../../../../../userfiles/modules/microweber/api/color.js";
import "../../../../../../userfiles/modules/microweber/api/tools/colorpicker.js";
import "../../../../../../src/MicroweberPackages/LiveEdit/resources/js/api-core/core/core/components.js";


//widgets
import "../../../../../../userfiles/modules/microweber/js/widgets/tree.js";
import {IconPicker} from "../../../../../../src/MicroweberPackages/LiveEdit/resources/js/api-core/services/services/icon-picker.js";



mw.tools = new AdminTools(mw.app);



mw.app.register('iconPicker', IconPicker);


mw.admin.filament = new AdminFilament();




mw.admin.dispatch('init');
mw.admin.dispatch('ready');





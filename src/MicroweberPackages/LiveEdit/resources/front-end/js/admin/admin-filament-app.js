





import "../core/@core.js";
import "./admin-filament-app.globals.js";
import "../core/url.js";
import "../core/events.js";





import {AdminTools} from "./admin-tools.service.js";
import {AdminFilament} from "./admin-filament.js";


window.mw.tools = new AdminTools(mw.app);





//libs
import "xss";

import TomSelect  from "tom-select";




globalThis.TomSelect = TomSelect;
window.TomSelect = TomSelect;





//core
import "../core/i18n.js";
import "../core/objects.js";
import "../core/icon-resolver.js";




import "../tools/loading.js";
import "../tools/storage.js";
import "../tools/element.js";
import "../tools/cookie.js";
import "../tools/common-extend.js";
import "../tools/tabs.js";
import "../tools/spinner.js";


//components
import "../components/icon_selector.js";
import "../components/tree.js";
import "../components/tags.js";



import "../components/dialog.js";

import "../components/autocomplete.js";
import "../components/form-controls.js";
import "../components/uploader.js";
import "../components/filemanager.js";

import "../components/filepicker.js";
import "../components/link-editor.js";
import "../components/color.js";
import "../components/colorpicker.js";
import "../components/components.js";


//widgets
import "../widgets/tree.js";
import {IconPicker} from "../widgets/icon-picker.js";







mw.app.register('iconPicker', IconPicker);


mw.admin.filament = new AdminFilament();




mw.admin.dispatch('init');
mw.admin.dispatch('ready');





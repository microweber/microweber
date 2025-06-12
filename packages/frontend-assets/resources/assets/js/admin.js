

import "./core.js";
import "../core/@core.js";
import "../core/_.js";
import "../core/ajax.js";
import "./admin-filament-app.globals.js";
import "../core/url.js";
import "../core/events.js";
import "../core/api.js";
import "../api-core/core/core/forms.js";
import "../../../../frontend-assets-libs/resources/local-libs/api/domtree.js";
import LiveEditImageDialog from "../live-edit/live-edit-image-dialog.js";

import {AdminFilamentColorThemeService, AdminTools} from "./admin-tools.service.js";
import {AdminFilament} from "./admin-filament.js";
import {AdminModules}  from "./admin-modules.js";

import {SingleFilePickerComponent}  from "../api-core/services/services/single-file-picker-component.js";




mw.tools = new AdminTools(mw.app);




mw.pause = time => new Promise(resolve => setTimeout(resolve, time || 0));


$.fn.commuter = function (a, b) {
    if (!a) { return }
    var b = b || function () {};
    return this.each(function () {
        if ((this.type === 'checkbox' || this.type === 'radio') && !this.cmactivated) {
            this.cmactivated = true;
            mw.$(this).on("change", function () {
                this.checked === true ? a.call(this) : b.call(this);
            });
        }
    });
};




mw.app.singleFilePickerComponent = options => {
    return new SingleFilePickerComponent(options)
};




//core
import "../core/i18n.js";
import "../core/objects.js";
import "../core/icon-resolver.js";
import {normalizeBase64Images, normalizeBase64Image} from "../tools/base64-images.js";





import {Progress, Loading} from "../tools/loading.js";
import {Helpers} from "../core/helpers.js";

mw.tools.progress = Progress;
mw.progress = Progress;
mw.tools.loading = Loading;

for ( let i in Helpers ) {
    mw.tools[i] = Helpers[i];
}




import "../tools/storage.js";
import "../tools/element.js";
import "../tools/cookie.js";
import "../tools/common-extend.js";
import "../tools/tabs.js";
import "../tools/images.js";
import "../tools/spinner.js";


//components
import "../components/icon_selector.js";
import "../components/tree.js";
import "../components/tags.js";
import "../components/uiaccordion.js";




import "../components/dialog.js";
import {Alert, Confirm, Prompt} from "../components/system-dialogs.js";
import {SchemaForm} from "../components/schema-form.js";

import {ControlBox} from "../components/control_box.js";
import {SystemDialogsService} from "../components/modules-dialogs.js";
import {TemplatePreview} from "../js/template-preview.service.js";



mw.app.editImageDialog =  new LiveEditImageDialog();
mw.app.adminModules =  new AdminModules();
mw.app.theme =  new AdminFilamentColorThemeService();

mw.alert = Alert;
mw.controlBox = ControlBox;
mw.confirm = Confirm;
mw.prompt = Prompt;

mw.app.normalizeBase64Image = normalizeBase64Image;
mw.app.normalizeBase64Images = normalizeBase64Images;

mw.app.register('colorPicker', ColorPicker);

mw.schemaForm = options => new SchemaForm(options);

mw.templatePreview = TemplatePreview;

for (let i in SystemDialogsService) {
    mw.tools[i] = SystemDialogsService[i];
}
mw.systemDialogsService = SystemDialogsService;


import "../components/autocomplete.js";
import "../components/select.js";
import "../components/form-controls.js";
import "../components/uploader.js";
import "../components/filemanager.js";

import "../components/filepicker.js";

import "../components/link-editor.js";
import "../components/color.js";
import "../components/colorpicker.js";
import "../components/components.js";
import "../components/notification.js";
import "../components/schema-form.js";


//widgets
import "../widgets/tree.js";
import "../widgets/dropdown.js";
import {CategoriesAdminListComponent} from "./categories-admin-list.component.js";
import { IconPicker } from "../widgets/icon-picker.js";
import { AdminPackageManager } from "./admin-package-manager.js";
import { ColorPicker } from "../api-core/services/services/color-picker.js";
import { HandleIcons } from "../api-core/core/handle-icons.js";





mw.app.register('iconPicker', IconPicker);
if(!mw.admin) {
    mw.admin = mw.app
}




mw.admin.admin_package_manager = new AdminPackageManager();

mw.admin.filament = new AdminFilament();
mw.admin.categoriesTree = (target, opt) => new CategoriesAdminListComponent(target, opt);

mw.app.register('iconService', HandleIcons);




mw.admin.dispatch('init');
mw.admin.dispatch('ready');






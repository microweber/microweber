import './index.js'

// Core globals needed by ControlBox and other components (mw.random, mw.id, etc.)
// These are normally set up by admin.js, but live-edit-app.js may load before admin.js
import "../../core/@core.js";

import {MWUniversalContainer} from "./containers/container.js";


import {Commands} from "./services/commands.js";
import {Modules} from "./services/modules.js";
import {Layouts} from "./services/layouts.js";
import {License} from "./services/license.js";
import {KeyboardEvents} from "./services/keyboard-events.js";
import {LivewireHooksReloadModule} from "./services/livewire-hooks-reload-module.js";
import {IconPicker} from "./services/icon-picker.js";
import {LinkPicker} from "./services/link-picker.js";
import {ColorPicker} from "./services/color-picker.js";

import {normalizeBase64Images, normalizeBase64Image} from "../../tools/base64-images.js";
//import '@nextapps-be/livewire-sortablejs';

// other libs
//import Alpine from 'alpinejs';
//import focus from '@alpinejs/focus';
import {DynamicTargetMenus} from './services/dynamic-target-menus.js';
import {LiveEditCanvas} from "./components/live-edit-canvas/live-edit-canvas.js";
import {SingleFilePickerComponent} from './services/single-file-picker-component.js';
import {MWBroadcast} from './services/broadcast.js';
import {MWDocumentFocus} from './services/document.focus.service.js';
import {MWPageAlreadyOpened} from './components/live-edit/page-already-opened.service.js';
import { HandleIcons } from '../core/handle-icons.js';
import {ControlBox} from "../../components/control_box.js";

if (!mw.controlBox) {
    mw.controlBox = ControlBox;
}

if (!mw.app) {
    mw.app = new MWUniversalContainer();

}



const canvasSetUrlInterceptor = async (url) => {
    return new Promise((resolve) => {
        if (mw.top().app.documentFocus.isActive()) {

        } else {

        }
    });
};


const canvas = new LiveEditCanvas({
    onSetUrl: canvasSetUrlInterceptor
});

mw.app.register('iconService', HandleIcons);



mw.app.register('documentFocus', MWDocumentFocus);
mw.app.register('broadcast', MWBroadcast);
mw.app.register('canvas', canvas);
mw.app.register('commands', Commands);
mw.app.register('modules', Modules);

mw.app.register('layouts', Layouts);
mw.app.register('license', License);
mw.app.register('keyboard', KeyboardEvents);
mw.app.register('iconPicker', IconPicker);
mw.app.register('linkPicker', LinkPicker);
mw.app.register('colorPicker', ColorPicker);
mw.app.register('dynamicTargetMenus', DynamicTargetMenus);
mw.app.register('pageAlreadyOpened', MWPageAlreadyOpened);
mw.app.register('pageAlreadyOpened', MWPageAlreadyOpened);



// task-2026-05-16-505ed5 / AI-708 — disambiguated heading per designer
// dispatch. This is the scope-picker panel (template vs layout); the
// renamed title "Templates & layouts" distinguishes it from the per-element
// "Element Style Editor" pane and the global "Theme settings" pane. The
// stale `mw-live-edit-templateSettings-editor-box` controlBox (was in
// `live-edit.blade.php`, also titled "Template Style Editor") has been
// removed alongside — see LESSONS / SOUL #110 for the DOM-duplicate audit.
//
// task-2026-05-17-bce4b7 / AI-800a — sentence-case + plural-plural
// cascade from AI-800 MainDrawer copy pass. The MainDrawer
// "Templates & layouts" nav-item is the entry point to this panel;
// the panel's own controlBox title must match the user's expectation
// set by that label. Cross-surface consistency outweighs the
// case-change regression test churn (handled via AI-708 contract-
// test pin-evolution).
mw.app.templateSettingsWidget = new mw.controlBox({
    content: `<div id="template-settings-teleport-widget-content"></div>`,
    position:  'right',
    id: `template-settings-teleport-widget`,
    closeButton: true,
    title: mw.lang('Templates & layouts')
});

// mw.app.templateSettingsWidgetSetupWizard = new mw.controlBox({
//     content: `<div id="template-settings-teleport-setup-wizard-content"></div>`,
//     position:  'right',
//     id: `template-settings-teleport-setup-wizard`,
//     closeButton: true,
//     title: mw.lang('Setup wizard')
// });
// mw.top().app.on('showSetupWizard', async function () {
//     mw.app.templateSettingsWidgetSetupWizard.show();
// });
//


mw.app.templateSettingsWidget.box.style.width = 'var(--sidebar-end-size)';
//mw.app.templateSettingsWidgetSetupWizard.box.style.width = 'var(--sidebar-end-size)';

    // AI-727 / task-2026-05-16-3b014a — guiEditor controlBox title
    // suppressed. The Vue ESE component at
    // `#mw-element-style-editor-app-container` already renders
    // `<h3 class="fs-2 font-weight-bold">Element styles</h3>` (per
    // AI-710 rename) as the canonical panel heading. The
    // mw.controlBox library was rendering a duplicate
    // `<h3 class="mw-control-box-title">Element Style Editor</h3>`
    // on the wrapper, causing screen readers to announce the
    // heading twice + showing two visible h3s when ESE opens.
    // Same defect class as AI-708 (Theme Settings / Template &
    // Layout duplicate h3); same fix shape — keep the wrapper div
    // for back-compat, suppress the duplicate h3 by passing
    // `title: false`. The controlBox library at
    // control_box.js:168 gates the h3 creation behind
    // `if (this.settings.title)`, so falsy = no h3 rendered.
    const guiEditor = new (mw.top()).controlBox({
        content: ``,
        position: 'right',
        id: 'mw-live-edit-gui-editor-box',
        closeButton: true,
        title: false
    });


    var eseApp = document.getElementById('mw-element-style-editor-app');
    if (eseApp) {
        guiEditor.boxContent.appendChild(eseApp);
    }

    mw.top().app.guiEditorBox = guiEditor


    // When Template Settings or the Element Style Editor opens, also
    // unmount any open Filament module/layout-settings slideOver — the
    // right-sidebar Tools buttons / SettingsCustomize / keyboard
    // shortcuts can all reach `.show()` without going through the
    // toolbar dropdown's `closeFilamentSlideOver()` helper, so close
    // here at the widget level to cover every entry point. See
    // task-2026-04-29-2315b5 / -70496a.
    const dispatchCloseFilament = () => {
        try { window.dispatchEvent(new Event('closeFilamentSlideOver')); } catch (_) {}
    };
    mw.app.templateSettingsWidget.on('show', () => {
        document.documentElement.classList['add']('live-edit-gui-editor-opened');
        dispatchCloseFilament();
    });
    guiEditor.on('show', () => {
        document.documentElement.classList['add']('live-edit-gui-editor-opened');
        dispatchCloseFilament();
    });
    guiEditor.on('hide', () => {
        if(!mw.controlBox.hasOpened('right')) {
            document.documentElement.classList['remove']('live-edit-gui-editor-opened');
        }


    });

     mw.app.templateSettingsWidget.on('hide', () => {
        if(!mw.controlBox.hasOpened('right')) {
            document.documentElement.classList['remove']('live-edit-gui-editor-opened');
        }


    });




mw.app.normalizeBase64Image = normalizeBase64Image;
mw.app.normalizeBase64Images = normalizeBase64Images;

mw.app.singleFilePickerComponent = options => {
    return new SingleFilePickerComponent(options)
};

mw.app.livewireHooksReloadModule = new LivewireHooksReloadModule();

let _strictMode = false;

mw.app.strictMode = val => {
    if(typeof val === "boolean") {
        _strictMode = val;
    }
    return _strictMode;
};


let sameUrlDialog = false;


const handleSameUrl = async () => {
    let url = mw.top().app.canvas.getUrl();

    const isLiveEdit = !!mw.top().app.canvas && mw.top().app.canvas.getWindow() && (self === top || self.frameElement.id === 'live-editor-frame');


    if (isLiveEdit && sameUrlDialog) {
        if (mw.top().app.canvas.getWindow() && mw.top().app.canvas.isUrlOpened(url) && mw.top().app.canvas.isUrlSame(url)) {


            const action = await mw.app.pageAlreadyOpened.handle(url);

            if (action) {

            } else {
                mw.top().win.location.href = mw.top().settings.adminUrl;
            }

        }
    }
}


mw.top().app.canvas.on('liveEditCanvasLoaded', async function () {
    await handleSameUrl();
});

mw.top().storage.change('mw-broadcast-data', async function () {

    await handleSameUrl();


});

mw.top().openModuleSettings = function (moduleId) {
    mw.app.moduleSettings.openModuleSettingsById(moduleId);
}



// document.addEventListener('livewire:init', () => {
//     alert('livewire:init')
// })
//mw.app.register('commands', Commands);

// mw.app.domTreeSelect = function(node) {
//     // deprecated
//     // if(!mw.top().app._liveEditDomTree) {
//     //     return;
//     // }
//     //
//     // mw.top().app._liveEditDomTree.select(node)
// }


//}, 300);

// init other libs
// window.Alpine = Alpine;
// Alpine.plugin(focus);

//Alpine.start();

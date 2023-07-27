import './index.js'

import {MWUniversalContainer} from "./containers/container.js";


import {EditorComponent} from "./components/editor/editor.js";
import {LiveEditCanvas} from "./components/live-edit-canvas/live-edit-canvas.js";
import {liveEditComponent} from "./components/live-edit/live-edit.js";
import {Commands} from "./services/commands.js";
import {Modules} from "./services/modules.js";
import {Layouts} from "./services/layouts.js";
import {KeyboardEvents} from  "./services/keyboard-events.js";
import {ModuleSettings} from "./services/module-settings";
import {IconPicker} from "./services/icon-picker";
import {LinkPicker} from "./services/link-picker";
import {ColorPicker} from "./services/color-picker";
import '@nextapps-be/livewire-sortablejs';

// other libs
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import { DynamicTargetMenus } from './services/dynamic-target-menus.js';

mw.app = new MWUniversalContainer();


//setTimeout(function() {

    mw.app.register('commands', Commands);
    mw.app.register('modules', Modules);

    mw.app.register('layouts', Layouts);
    mw.app.register('keyboard', KeyboardEvents);
    mw.app.register('iconPicker', IconPicker);
    mw.app.register('linkPicker', LinkPicker);
    mw.app.register('colorPicker', ColorPicker);
    mw.app.register('dynamicTargetMenus', DynamicTargetMenus);


//mw.app.register('commands', Commands);

mw.app.domTreeSelect = function(node) {
    if(!mw.top().app._liveEditDomTree) {
        return;
    }

    mw.top().app._liveEditDomTree.select(node)
}


//}, 300);

// init other libs
window.Alpine = Alpine;
Alpine.plugin(focus);

Alpine.start();

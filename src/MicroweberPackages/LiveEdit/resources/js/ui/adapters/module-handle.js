 import BaseComponent from "../../api-core/services/containers/base-class.js";
 import { insertModule } from "../../api-core/services/services/insert-module";

class ModuleHandleAdapter extends BaseComponent {
    constructor() {
        super();
        const handle = mw.app.liveEdit.moduleHandle;
        handle.on('targetChange', taget => {
            this.dispatch('targetChange', taget)
        })
    }

}

class ElementHandleAdapter extends BaseComponent {
    constructor() {
        super();
        const handle = mw.app.liveEdit.elementHandle;
        handle.on('targetChange', taget => {
            this.dispatch('targetChange', taget)
        })
    }
}

class LayoutHandleAdapter extends BaseComponent {
    constructor() {
        super();
        const handle = mw.app.liveEdit.layoutHandleContent;
        handle.on('targetChange', taget => {
            this.dispatch('targetChange', taget)
        })
    }
}


export class EditorHandles extends BaseComponent {
    constructor() {
        super();
    }
    handle = {
        module:  new ModuleHandleAdapter(),
        element: new ElementHandleAdapter(),
        layout: new LayoutHandleAdapter(),
    }

    insertModule(module, options, insertLocation = 'bottom') {
        let target = mw.app.liveEdit.handles.get('element').getTarget();
        if(!target) {
            target = mw.app.liveEdit.handles.get('module').getTarget();
        }


        return insertModule(target, module, options, insertLocation)
    };

    insertLayout(options, insertLocation = 'top', target) {
        if(!target) {
            target = mw.app.liveEdit.handles.get('layout').getTarget()
        }
        
        return insertModule(target, 'layouts', options, insertLocation);
    };

};





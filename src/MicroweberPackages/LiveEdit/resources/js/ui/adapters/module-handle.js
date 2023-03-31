import BaseComponent from "bootstrap/js/dist/base-component";
import { insertModule } from "../../api-core/services/services/insert-module";

class ModuleHandleAdapter extends BaseComponent {
    constructor() {
        super();
        const handle = mw.app.get('liveEdit').moduleHandle;
        handle.on('targetChange', taget => {
            this.dispatch('targetChange', taget)
        })
    }

} 

class ElementHandleAdapter extends BaseComponent {
    constructor() {
        super();
        const handle = mw.app.get('liveEdit').elementHandle;
        handle.on('targetChange', taget => {
            this.dispatch('targetChange', taget)
        })
    }
} 

class LayoutHandleAdapter extends BaseComponent {
    constructor() {
        super();
        const handle = mw.app.get('liveEdit').layoutHandleContent;
        handle.on('targetChange', taget => {
            this.dispatch('targetChange', taget)
        })
    }
} 


const EditorHandles = {
    handle: {
        module:  new ModuleHandleAdapter(),
        element: new ElementHandleAdapter(),
        layout: new LayoutHandleAdapter(),
    },

};

EditorHandles.insertModule = (module, options) => {
    const target = mw.app.get('liveEdit').handles.get('module').getTarget()
    return insertModule(target, module, options)
};

EditorHandles.insertLayout = (options) => {
    const target = mw.app.get('liveEdit').handles.get('layout').getTarget()
    return insertModule(target, 'layouts', options)
};

export default EditorHandles;
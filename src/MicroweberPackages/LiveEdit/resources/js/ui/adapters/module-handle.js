
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


const editor = {
    handle: {
        module:  new ModuleHandleAdapter(),
        element: new ElementHandleAdapter(),
        layout: new LayoutHandleAdapter(),
    },

};

mw.app.register('editor', editor);
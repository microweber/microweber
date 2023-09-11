
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



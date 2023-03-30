
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



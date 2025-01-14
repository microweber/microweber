
import BaseComponent from "../containers/base-class.js";


export class LiveEditWidgetsService extends BaseComponent{
    constructor(){
        super();
    }

    status = {
       adminSidebarOpened: false,
       layersOpened: false,
    }

    #hasOpened() {
        for( let i in this.status) {
            if(this.status[i]) {
                return true;
            }
        }
        return false;
    }

    closeAll() {
        this.closeAdminSidebar()
        this.closeLayers()
    }


    toggleAdminSidebar() {
        return this[this.status.adminSidebarOpened ? 'closeAdminSidebar' : 'openAdminSidebar']();
    }

    openAdminSidebar() {
        this.status.adminSidebarOpened = true;
        mw.top().doc.querySelector('aside.fi-sidebar').classList.add('active')
        mw.top().doc.documentElement.classList.add( 'mw-live-edit-sidebar-start');
        this.dispatch('adminSidebarOpen');
        return this;

    }

    closeAdminSidebar() {
        if(!this.status.adminSidebarOpened) {
            return this;
        }
        this.status.adminSidebarOpened = false;
        mw.top().doc.querySelector('aside.fi-sidebar').classList.remove('active');
        if(!this.#hasOpened()) {
            mw.top().doc.documentElement.classList.remove( 'mw-live-edit-sidebar-start');

        }
        this.dispatch('adminSidebarClose');
        return this;
    }


    openLayers() {
        this.status.layersOpened = true;
        mw.top().app.domTree.show();
        mw.top().doc.documentElement.classList.add( 'mw-live-edit-sidebar-start');
        this.dispatch('layersOpen');
        return this;

    }

    closeLayers() {
        if(!this.status.layersOpened) {
            return this;
        }
        this.status.layersOpened = false;
        mw.top().app.domTree.hide();
        if(!this.#hasOpened()) {
            mw.top().doc.documentElement.classList.remove( 'mw-live-edit-sidebar-start');

        }
        this.dispatch('layersClose');
        return this;

    }

    toggleLayers() {
        return this[this.status.layersOpened ? 'closeLayers' : 'openLayers']();
    }

}

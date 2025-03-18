
import { QuickEditComponent } from "../components/quick-ai-edit.js";
import BaseComponent from "../containers/base-class.js";



export class LiveEditWidgetsService extends BaseComponent{
    constructor(){
        super();
        this.quickEditComponent = new QuickEditComponent();
    }

    status = {
       adminSidebarOpened: false,
       layersOpened: false,
       quickEditComponent: false,
    }

    #hasOpened() {
        for( let i in this.status) {
            if(this.status[i]) {
                return true;
            }
        }
        return false;
    }

    #zIndex(target) {
        const treeBox = mw.top().app.domTree.box.box;
        const adminBox = mw.top().doc.querySelector('aside.fi-sidebar');
        [treeBox, adminBox].forEach(box => {
            box.style.setProperty('z-index', (box === target ? 101 : 99), 'important');
        })

    }

    closeAll() {
        this.closeAdminSidebar()
        this.closeLayers()
        this.closeQuickEditComponent()
        this.#zIndex()
    }

    #closeQuickEditComponentBox = null;


    closeQuickEditComponent() {
        if( this.status.quickEditComponent ) {
            this.quickEditComponent.destroyEditor()
            this.#closeQuickEditComponentBox?.remove();
            this.status.quickEditComponent = false;
        }

    }

    openQuickEditComponent() {
        this.closeAll();
        this.status.quickEditComponent = true;

        const box = new (mw.top()).controlBox({
            content:``,
            position:  'right',
            id: 'mw-live-edit-quickEditComponent-box',
            closeButton: true,
            closeButtonAction: 'remove',
            title: mw.lang('Quick Edit'),
            width: 'var(--sidebar-end-size)'
        });

        this.#closeQuickEditComponentBox = box;

        box.boxContent.appendChild(this.quickEditComponent.editor());

        box.show();
        this.status.quickEditComponent = true;

        box.on('remove', () => {

            this.quickEditComponent.destroyEditor()

            this.status.quickEditComponent = false;
        })

        this.dispatch('openQuickEditComponent');
        return this;

    }

    toggleQuickEditComponent() {
        return this[this.status.quickEditComponent ? 'closeQuickEditComponent' : 'openQuickEditComponent']();
    }

    toggleAdminSidebar() {
        return this[this.status.adminSidebarOpened ? 'closeAdminSidebar' : 'openAdminSidebar']();
    }

    openAdminSidebar() {
        this.closeAll();
        this.status.adminSidebarOpened = true;
        mw.top().doc.querySelector('aside.fi-sidebar').classList.add('active')
        mw.top().doc.documentElement.classList.add( 'mw-live-edit-sidebar-start');
        this.#zIndex(mw.top().doc.querySelector('aside.fi-sidebar'));
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
        this.closeAll();
        this.status.layersOpened = true;
        this.#zIndex(mw.top().app.domTree.box.box);
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

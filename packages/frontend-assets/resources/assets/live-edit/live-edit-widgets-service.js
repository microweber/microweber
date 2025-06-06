
import { QuickEditComponent } from "../components/quick-ai-edit.js";
import BaseComponent from "../containers/base-class.js";



export class LiveEditWidgetsService extends BaseComponent{
    constructor(){
        super();
        this.quickEditor();
    }

    quickEditor(options) {
        if(this.quickEditComponent) {
            this.quickEditComponent.destroyEditor();
        }
        this.quickEditComponent = new QuickEditComponent(options);

    }

    setQuickEditorForNode(node) {
         if(!node) {
            console.log(node, 'is not defined');
            return;
         }
         this.quickEditor({
            root: node
         });

        this.quickEditComponentBox.boxContent.innerHTML = '';
        this.quickEditComponentBox.boxContent.appendChild(this.quickEditComponent.editor());

        console.log(this.quickEditComponent.editor())
        console.log(this.quickEditComponentBox.boxContent)

        this.quickEditComponentBox.show();
        this.status.quickEditComponent = true;
    }

    status = {
       adminSidebarOpened: false,
       layersOpened: false,
       quickEditComponent: false,
    }

    #hasOpened() {
        if(document.querySelector('#general-theme-settings.active')) {
            return true;
        }
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
        // this.closeLayers()
        this.closeQuickEditComponent()
        this.#zIndex()
    }

    #closeQuickEditComponentBox = null;


    closeQuickEditComponent() {
        if( this.status.quickEditComponent ) {
            this.quickEditComponent.destroyEditor()
            this.#closeQuickEditComponentBox?.remove();
            this.status.quickEditComponent = false;



            if(!mw.top().controlBox.hasOpened('right')) {
                mw.top().doc.documentElement.classList.remove('live-edit-gui-editor-opened');
            }

            this.dispatch('closeQuickEditComponent');

        }

    }

    openQuickEditComponent() {

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

        this.quickEditComponentBox = box;

        this.#closeQuickEditComponentBox = box;

        box.boxContent.appendChild(this.quickEditComponent.editor());

        box.show();
        this.status.quickEditComponent = true;

        box.on('remove', () => {

            this.quickEditComponent.destroyEditor()

            this.status.quickEditComponent = false;

        })

        this.dispatch('openQuickEditComponent');
        mw.top().doc.documentElement.classList.add('live-edit-gui-editor-opened');
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

    closeLayersSidebar() {
        this.status.layersOpened = false;

        if(!this.#hasOpened()) {
            mw.top().doc.documentElement.classList.remove( 'mw-live-edit-sidebar-start');

        }
        this.dispatch('layersClose');
    }
    closeLayers() {
        if(!this.status.layersOpened) {
            return this;
        }

        mw.top().app.domTree.hide();
        this.closeLayersSidebar()

        return this;

    }

    toggleLayers() {
        return this[this.status.layersOpened ? 'closeLayers' : 'openLayers']();
    }

}

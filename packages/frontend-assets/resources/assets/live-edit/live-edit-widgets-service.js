
import { QuickEditComponent } from "../components/quick-ai-edit.js";
import BaseComponent from "../containers/base-class.js";

import {ElementManager} from "../api-core/core/classes/element.js";

export class LiveEditWidgetsService extends BaseComponent{
    constructor(){
        super();
        this.quickEditor();
    }

    quickEditor(options) {

        const handleTargetChange = target => {
            if(!mw.app.liveEditWidgets.quickEditComponent.editorNodes) {
                return;
            }
            const field = mw.app.liveEditWidgets.quickEditComponent.editorNodes.find(node => node.$$ref && node.$$ref.node === target);
            if(field) {
                const input = field.querySelector('input,select,textarea');
                if(input && input.ownerDocument.activeElement !== input) {
                    field.scrollIntoView({behavior: "smooth", block: "center", inline: "start"});
                    input.style.outline = '8px solid #008B8B';
                    input.style.transition = '.5s';
                    setTimeout(() => {
                        input.style.outline ='0px solid #008B8B';

                    }, 1200);
                }

            }
        }

        if(this.quickEditComponent) {
            this.quickEditComponent.destroyEditor();
             mw.top().app.liveEdit.elementHandle.off('targetChange', handleTargetChange)
        }
        this.quickEditComponent = new QuickEditComponent(options);


        mw.top().app.liveEdit.elementHandle.on('targetChange', handleTargetChange);

        if(mw.top().app.liveEditWidgets) {
            if(mw.top().app.liveEditWidgets.status.quickEditComponent) {
                setTimeout(() => {
                    mw.top().app.liveEditWidgets.openQuickEditComponent();
                }, 110)
            }
        }

    }

    setQuickEditorForNode(node) {
         if(!node) {
            console.log(node, 'is not defined');
            return;
         }




         if(node.nodeName !== 'BODY'&& this.#mode === 'page') {
            return;
        }

         this.quickEditor({
            root: node
         });
         const children = Array.from(this.quickEditComponentBox.boxContent.children);

        this.quickEditComponentBox.boxContent.appendChild(this.quickEditComponent.editor());

        for (let i = 0; i < children.length; i++) {
            children[i].remove()
        }



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
            this.#closeQuickEditComponentBox?.hide();
            setTimeout(() => {
                this.#closeQuickEditComponentBox?.remove();
            }, 500)

            this.status.quickEditComponent = false;



            if(!mw.top().controlBox.hasOpened('right')) {
                mw.top().doc.documentElement.classList.remove('live-edit-gui-editor-opened');
            }

            this.dispatch('closeQuickEditComponent');

        }

    }

    #mode = 'page';

    openQuickEditComponent() {
        const isWholePage = mw.top().app.liveEditWidgets.quickEditComponent.settings.root === mw.top().app.canvas.getDocument().body;



        this.status.quickEditComponent = true;

            mw.top().controlBox.getInstances().forEach(instance => {
                if(instance.id === 'mw-live-edit-quickEditComponent-box') {
                    instance.remove()
                }
            })



        const closeButtonAction = () => {
            this.closeQuickEditComponent();
            this.status.quickEditComponent = false;
        }



        const tabs = ElementManager(`
            <div class="flex gap-4 mb-4 items-center">

                     <button type="button" class="btn ${isWholePage ? '' : 'active'}" data-target="layout">Active layout</button>

                     <button type="button" class="btn ${isWholePage ? 'active' : ''}"  data-target="page">Whole page</button>

            </div>
        `).get(0);




        tabs.addEventListener("click", (e) => {
            const target = e.target.closest("button:not(.active)");


            if(target) {

                const action = target.dataset.target;

                this.#mode = action;

                if(action === 'page') {

                    this.setQuickEditorForNode(mw.top().app.canvas.getDocument().body)
                } else if(action === 'layout') {

                    let activeLayout = mw.top().app.liveEdit.layoutHandle.getTarget();

                    if(!activeLayout) {
                        const activeElement = mw.top().app.liveEdit.elementHandle.getTarget();
                        if(activeElement) {
                            activeLayout = activeElement.closest('.module-layouts');
                        }
                    }

                    if(activeLayout) {
                        this.setQuickEditorForNode(activeLayout)
                    }


                }
            }

        })

        const box = new (mw.top()).controlBox({
            content:``,
            position:  'right',
            id: 'mw-live-edit-quickEditComponent-box',
            closeButton: true,
            closeButtonAction: closeButtonAction,
            title: mw.lang('Quick AI Edit'), // task-2026-05-22-902abc / AI-902 — matches button tooltip
            width: 'var(--sidebar-end-size)'
        });

        this.quickEditComponentBox = box;

        this.#closeQuickEditComponentBox = box;

        box.boxContent.appendChild(tabs);
        box.boxContent.appendChild(this.quickEditComponent.editor());

        box.show();
        this.status.quickEditComponent = true;

        box.on('remove', () => {

            this.quickEditComponent.destroyEditor()

            this.status.quickEditComponent = false;

        })

        this.dispatch('openQuickEditComponent');
        mw.top().doc.documentElement.classList.add('live-edit-gui-editor-opened');
        try { window.dispatchEvent(new Event('closeFilamentSlideOver')); } catch (_) {}
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
        try { window.dispatchEvent(new Event('closeFilamentSlideOver')); } catch (_) {}
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

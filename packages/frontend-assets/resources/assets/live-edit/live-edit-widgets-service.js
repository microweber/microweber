
import { QuickEditComponent } from "../components/quick-ai-edit.js";
import { MwAiConversation } from "../components/mw-ai-conversation.js";
import BaseComponent from "../containers/base-class.js";

import {ElementManager} from "../api-core/core/classes/element.js";

export class LiveEditWidgetsService extends BaseComponent{
    constructor(){
        super();
        this.quickEditor();
        this.#bindAutoCloseAdminSidebar();
    }

    // Auto-close the admin nav drawer whenever ANY other editing panel opens
    // (module settings, quick-settings panel, module/preset/layout pickers, the
    // insert-module search). The admin sidebar is a LEFT overlay in Live Edit and
    // would otherwise sit on top of / compete with the panel the user just opened.
    // closeAdminSidebar() is a no-op when the drawer isn't open, so this is safe
    // to fire on every open signal. Reuses existing events where they already
    // exist (mwQuickSettingsWillOpen, openModuleSettingsAction) instead of adding
    // new ones.
    #bindAutoCloseAdminSidebar() {
        const closeIfOpen = () => { try { this.closeAdminSidebar(); } catch (e) {} };
        // Editor-level open requests (mw.app.editor event bus). NOTE: this service
        // is constructed BEFORE `mw.app.editor` exists (live-edit.js creates the
        // widgets service, then the editor on the next line), so binding here at
        // construction silently no-ops. Defer to `mw.app.on('ready')`, and also
        // try once immediately in case the editor already exists.
        let editorBound = false;
        const bindEditor = () => {
            if (editorBound) { return true; }
            try {
                const ed = mw.top().app.editor;
                if (ed && typeof ed.on === 'function') {
                    editorBound = true;
                    ['onModuleSettingsRequest', 'onLayoutSettingsRequest', 'onModulePresetsRequest',
                     'insertModuleRequest', 'insertFreeModuleRequest'].forEach((ev) => ed.on(ev, closeIfOpen));
                }
            } catch (e) {}
            return editorBound;
        };
        // Poll until the editor exists — it is created on the line AFTER this
        // service in live-edit.js, so it is never present at construction and the
        // 'ready' event proved unreliable here. Retry briefly on a timer instead.
        const tryBind = (attempts) => {
            if (bindEditor() || attempts <= 0) { return; }
            setTimeout(() => tryBind(attempts - 1), 100);
        };
        tryBind(60);
        // DOM-level panel-open announcements dispatched on the top window
        // (quick-settings kit + Btn panel → mwQuickSettingsWillOpen;
        //  Filament module / layout / preset settings slide-over → openModuleSettingsAction).
        try {
            const w = (mw.top().doc && mw.top().doc.defaultView) || window;
            ['mwQuickSettingsWillOpen', 'openModuleSettingsAction'].forEach((ev) =>
                w.addEventListener(ev, closeIfOpen, true));
        } catch (e) {}
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

        // Primary AI surface: a Claude-style conversation that edits the live
        // site via streamed frontend tool calls (see MwAiConversation). The
        // classic field editor stays available behind an "Edit fields" tab so
        // its existing behaviour + Dusk hooks are preserved.
        let _contentId = 0;
        try {
            const _led = mw.top().app.canvas.getLiveEditData();
            _contentId = (_led && _led.content && _led.content.id) ? _led.content.id : 0;
        } catch (_) {}

        this.aiConversation = new MwAiConversation({ contentId: _contentId });

        const modeBar = ElementManager(`
            <div class="mw-ai-quick-modes flex gap-2 mb-3">
                <button type="button" class="btn active" data-mode="chat">${mw.lang('AI Chat')}</button>
                <button type="button" class="btn" data-mode="fields">${mw.lang('Edit fields')}</button>
            </div>
        `).get(0);

        const chatView = this.aiConversation.root;
        chatView.style.minHeight = '60vh';

        const fieldsView = document.createElement('div');
        fieldsView.style.display = 'none';
        fieldsView.appendChild(tabs);
        fieldsView.appendChild(this.quickEditComponent.editor());

        modeBar.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-mode]');
            if (!btn) { return; }
            modeBar.querySelectorAll('button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const isChat = btn.dataset.mode === 'chat';
            chatView.style.display = isChat ? '' : 'none';
            fieldsView.style.display = isChat ? 'none' : '';
        });

        box.boxContent.appendChild(modeBar);
        box.boxContent.appendChild(chatView);
        box.boxContent.appendChild(fieldsView);

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
        const adminSidebarEl = mw.top().doc.querySelector('aside.fi-sidebar');
        adminSidebarEl.classList.add('active')
        // task-2026-09-24 — pin the admin nav as a LEFT drawer below the toolbar.
        // NO transform / NO transition: Filament's base `transition:all` animated
        // the left<->right change and made the sidebar fly across the screen; we
        // kill the transition so it just appears on the left instantly (per user
        // request to remove the transform). Inline !important beats Filament's own
        // transitions/translate and the prior right-positioning rule.
        const _tb = 'var(--toolbar-height, 48px)';
        adminSidebarEl.style.setProperty('position', 'fixed', 'important');
        adminSidebarEl.style.setProperty('left', '0', 'important');
        adminSidebarEl.style.setProperty('right', 'auto', 'important');
        adminSidebarEl.style.setProperty('inset-inline-start', '0', 'important');
        adminSidebarEl.style.setProperty('inset-inline-end', 'auto', 'important');
        adminSidebarEl.style.setProperty('top', _tb, 'important');
        adminSidebarEl.style.setProperty('height', 'calc(100dvh - ' + _tb + ')', 'important');
        adminSidebarEl.style.setProperty('transition', 'none', 'important');
        adminSidebarEl.style.setProperty('transform', 'none', 'important');
        adminSidebarEl.style.setProperty('translate', 'none', 'important');
        // task-2026-09-24 — hide the sidebar's logo header in Live Edit; the
        // "Back to admin" row + the nav are enough, the Microweber logo header is
        // redundant here.
        const _hdr = adminSidebarEl.querySelector('.fi-sidebar-header-ctn') || adminSidebarEl.querySelector('.fi-sidebar-header');
        if (_hdr) { _hdr.style.setProperty('display', 'none', 'important'); }
        // "Back to admin" affordance at the top of the drawer.
        this.#injectAdminBackButton(adminSidebarEl);
        // `mw-live-edit-sidebar-start` drives the admin sidebar's expanded
        // label styling (live-edit-mobile.css). `mw-live-edit-admin-open`
        // (task-2026-09-06-darkaudit) additionally marks that the drawer is the
        // RIGHT admin overlay — the canvas-shift rule excludes it via
        // :not(.mw-live-edit-admin-open) so the canvas is NOT pushed 250px right
        // (which left a black gap on the left). The Layers panel keeps its left
        // shift because it only sets `mw-live-edit-sidebar-start`.
        mw.top().doc.documentElement.classList.add( 'mw-live-edit-sidebar-start', 'mw-live-edit-admin-open');
        this.#zIndex(mw.top().doc.querySelector('aside.fi-sidebar'));
        // Flip Filament's Alpine sidebar store to "open" so the sidebar renders
        // EXPANDED: item labels show (x-show="$store.sidebar.isOpen") AND grouped
        // nav (Website / Shop) renders as labelled headers with their sub-items
        // instead of unlabelled icon-only dropdown triggers. Without this,
        // live-edit reveals the sidebar via `.active` but Filament still thinks
        // it's collapsed, so every label is hidden. Pairs with the CSS in
        // live-edit-mobile.css (html.mw-live-edit-sidebar-start …) which shows
        // the labels + left-aligns the icons.
        this.#setFilamentSidebarOpen(true);
        this.#bindAdminOverlayClose();
        this.dispatch('adminSidebarOpen');
        return this;

    }

    // task-2026-09-05-adminrail — mobile/tablet outside-tap close.
    // On viewports < lg (1024px) Filament shows `.fi-sidebar-close-overlay`
    // (z-index 30) whenever its Alpine sidebar store is open — which we flip
    // on via #setFilamentSidebarOpen(true). That backdrop covers the right
    // rail (z-index 2), so the rail "Admin" toggle can't be tapped to close,
    // and the overlay's own handler only calls $store.sidebar.close() (flips
    // the store, NOT our `.active` class) — leaving the drawer stuck open.
    // Bind a click on the overlay that fully closes our drawer too. Guarded
    // on the element (dataset) so it's attached at most once even across
    // repeated opens. On desktop the overlay is `lg:hidden`, so this is a
    // no-op there.
    #bindAdminOverlayClose() {
        try {
            const overlay = mw.top().doc.querySelector('.fi-sidebar-close-overlay');
            if (!overlay || overlay.dataset.mwAdminCloseBound) {
                return;
            }
            overlay.dataset.mwAdminCloseBound = '1';
            overlay.addEventListener('click', () => {
                if (this.status.adminSidebarOpened) {
                    this.closeAdminSidebar();
                }
            });
        } catch (e) { /* no-op — rail Admin button remains the close path */ }
    }

    #setFilamentSidebarOpen(isOpen) {
        try {
            var topWin = mw.top().doc.defaultView;
            var Alpine = (topWin && topWin.Alpine) || (typeof window !== 'undefined' && window.Alpine);
            if (Alpine && typeof Alpine.store === 'function') {
                var sb = Alpine.store('sidebar');
                if (sb) { sb.isOpen = isOpen; }
            }
        } catch (e) { /* no-op — sidebar labels degrade to icon-only */ }
    }

    // task-2026-09-24 — prepend a "Back to admin" shortcut to the admin sidebar
    // (once) that leaves Live Edit for the full admin dashboard.
    #injectAdminBackButton(sidebarEl) {
        try {
            if (!sidebarEl || sidebarEl.querySelector('.mw-le-admin-back')) { return; }
            const doc = sidebarEl.ownerDocument;
            let url = '/admin';
            try {
                url = (typeof mw !== 'undefined' && mw.settings && mw.settings.adminUrl)
                    ? mw.settings.adminUrl
                    : ((mw.top().mw && mw.top().mw.settings && mw.top().mw.settings.adminUrl) || '/admin');
            } catch (e) {}
            const a = doc.createElement('a');
            a.className = 'mw-le-admin-back';
            a.href = url;
            a.setAttribute('aria-label', 'Back to admin');
            a.setAttribute('title', 'Back to admin');
            a.style.cssText = 'display:flex;align-items:center;gap:8px;padding:12px 16px;font-weight:600;font-size:13px;color:inherit;text-decoration:none;border-bottom:1px solid rgba(128,128,128,.2);flex:0 0 auto;';
            a.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 -960 960 960" fill="currentColor" aria-hidden="true"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg><span>Back to admin</span>';
            sidebarEl.insertBefore(a, sidebarEl.firstChild);
        } catch (e) { /* non-blocking — sidebar still usable without the shortcut */ }
    }

    closeAdminSidebar() {
        if(!this.status.adminSidebarOpened) {
            return this;
        }
        this.status.adminSidebarOpened = false;
        const sb = mw.top().doc.querySelector('aside.fi-sidebar');
        // task-2026-09-24 — instant teardown (no slide). Hide first (.active
        // removed), then clear the drawer inline styles; with no transition there's
        // no fly-out and no "collapses to an icon strip in the middle" flash.
        if (sb) {
            sb.classList.remove('active');
            ['position', 'left', 'right', 'inset-inline-start', 'inset-inline-end', 'top', 'height', 'transition', 'transform', 'translate']
                .forEach((p) => sb.style.removeProperty(p));
            // restore the logo header we hid on open
            const hdr = sb.querySelector('.fi-sidebar-header-ctn') || sb.querySelector('.fi-sidebar-header');
            if (hdr) { hdr.style.removeProperty('display'); }
        }
        // Restore Filament's collapsed state so we don't leave its own mobile
        // sidebar overlay flagged open after the live-edit panel closes.
        this.#setFilamentSidebarOpen(false);
        mw.top().doc.documentElement.classList.remove('mw-live-edit-admin-open');
        if(!this.#hasOpened()) {
            mw.top().doc.documentElement.classList.remove('mw-live-edit-sidebar-start');
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

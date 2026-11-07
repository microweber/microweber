<style>


.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar .mw-live-edit-advanced-settings-popup svg {


    fill: currentColor !important;
    margin: 0 !important;
    margin-inline: 0 !important;

    width: 25px !important;
    max-width: none !important;
    height: auto !important;
    max-height: none !important;
}


.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar .mw-live-edit-advanced-settings-popup,
.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar .btn-icon.live-edit-toolbar-buttons {
    height: 44px !important;
    width: 44px !important;
    min-height: 44px !important;
    max-height: 44px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    margin: 0 auto !important;
    gap: 4px;
    user-select: none;
    overflow: visible !important;

    svg {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }
}


/* task-2026-05-22-903d56 / AI-903: Advanced panel shows text in menu mode (via template=menu, not CSS override) */

.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar ul {
    display: flex;
    flex-direction: column;
    align-items: center !important;
    justify-content: start !important;
    width: 100%;
    gap: 22px !important;
}

.mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 56px;
    height: calc(100vh - var(--toolbar-height));
    transition: transform .5s, right .5s;
    display: flex;
    flex-direction: column;
    align-items: center !important;
    justify-content: start !important;
    max-height: none !important;
    gap: 16px !important;
    padding: 10px 0 !important;
    margin: 0 !important;
    border-radius: 0 !important;
    z-index: 2;
    background-color: var(--tblr-bg-surface, #ffffff);
    border-left: 1px solid rgba(0, 0, 0, 0.08);
}

.dark .mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar {
    background-color: var(--tblr-bg-surface, #182433);
    border-left: 1px solid rgba(255, 255, 255, 0.1);
}

.live-edit-gui-editor-opened .mw-live-edit-right-sidebar-wrapper.mw-live-edit-right-sidebar-template-sidebar {
    right: var(--sidebar-end-size);
}



</style>


<template>


    <div :class="'mw-live-edit-right-sidebar-template-' + template" class="mw-live-edit-right-sidebar-wrapper me-2 ">

        <!-- task-2026-09-24 — Admin button moved OUT of the right rail to the
             top-left toolbar (Toolbar.vue). handleAdmin()/buttonIsActiveAdmin
             below are retained (still wired to the adminSidebar events) in case
             other code references them, but no button renders here now. -->


        <!-- AI-64 / TICKET-PP (cycle-77 2026-05-08): these icon
             toolbar controls were converted from <div>/<span> with
             role="button" to real <button> elements. role="button"
             gets the right AT semantics but real <button>:
               - participates in form submission semantics correctly
               - gets the browser's native focus ring (free)
               - fires click on Space-keyup automatically (no manual
                 @keydown.space handler needed)
               - shows the right cursor by default
               - works with disabled, form, autofocus attributes
             The keydown handlers are kept as a belt-and-braces for
             environments where the role="button" pattern was being
             relied on (some Vuetify-style overlays add their own
             keydown listeners that prevent the native behaviour).

             AI-64 also calls for 44×44 minimum touch targets — the
             sizing override lives in mobile-touch.css alongside the
             other live-edit chrome rules. -->
        <!-- task-2026-05-21-a4832f / AI-871: data-mw-label added for the
             mini-label ::after CSS pattern (2-3 char label below icon). -->
        <button type="button"
             v-if="insertLayoutVisible"
             class="btn-icon live-edit-toolbar-buttons mw-toolbar-icon-btn"
             aria-label="Insert layout"
             title="Insert layout"
             data-mw-label="Layout"
             data-mw-sidebar-priority="secondary"
             v-on:click="handleInsertLayout()"
             v-on:keydown.enter.prevent="handleInsertLayout()"
             v-on:keydown.space.prevent="handleInsertLayout()">
            <v-tooltip activator="parent" location="start">
                <Lang>Insert layout</Lang>
            </v-tooltip>
            <span v-html="iconInsertlayout" aria-hidden="true"></span>
        </button>


        <!--
          task-2026-05-16-505ed5 / AI-708 — button label disambiguated.
          This button opens the scope-picker panel (template vs layout).
          The `toggle()` target string 'template-settings' is left
          unchanged — it's an internal state key, not user-visible copy.

          task-2026-05-17-c3f232 / AI-800b — cross-surface case cascade
          continued from AI-800a. The right-rail button is a SECOND
          entry point to the same panel; the MainDrawer + bootstrap.js
          controlBox + RightSidebar h3 all carry "Templates & layouts"
          (sentence-case plural-plural per AI-708 / AI-800 / AI-800a).
          Asymmetry between two entry points labelling the same destination
          differently is a worse defect than the pre-AI-800 state. Both
          aria-label + title + <Lang> tooltip text now match the panel
          + drawer + controlBox shape for every entry point.
        -->
        <button type="button"
              v-if="canShowSettingsCustomize"
              :class="{'live-edit-right-sidebar-active': buttonIsActive && !buttonIsActiveStyleEditor }"
              class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-css-editor-toggle mw-toolbar-icon-btn"
              aria-label="Templates & layouts"
              title="Templates & layouts"
              data-mw-label="Template"
              :aria-pressed="buttonIsActive && !buttonIsActiveStyleEditor"
              v-on:click="toggle('template-settings')"
              v-on:keydown.enter.prevent="toggle('template-settings')"
              v-on:keydown.space.prevent="toggle('template-settings')">
            <v-tooltip activator="parent" location="start"><Lang>Templates &amp; layouts</Lang></v-tooltip>

            <svg fill="currentColor" height="22" viewBox="96 96 960 960" width="22" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path
                d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/></svg>
        </button>


        <!-- AI-710 / task-2026-05-16-6c4e74 — right-rail icon was
             labelled "Design" but opens the per-element styling panel;
             rename to "Element styles" so the button and the panel
             agree (screen readers stop announcing two different names
             for the same surface). Click target / state classes / event
             handlers are unchanged — pure label rewrite. -->
        <button type="button"
             v-if="canShowSettingsCustomize"
             :class="{'live-edit-right-sidebar-active': !buttonIsActive && buttonIsActiveStyleEditor }"
             class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-css-editor-toggle mw-toolbar-icon-btn"
             aria-label="Element styles"
             title="Element styles"
             data-mw-label="Style"
             :aria-pressed="!buttonIsActive && buttonIsActiveStyleEditor"
             v-on:click="toggle('style-editor')"
             v-on:keydown.enter.prevent="toggle('style-editor')"
             v-on:keydown.space.prevent="toggle('style-editor')">
            <v-tooltip activator="parent" location="start">
                <Lang>Element styles</Lang>
            </v-tooltip>
            <svg fill="currentColor"
                 height="22" viewBox="0 -960 960 960"
                 width="22" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path
                    d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"/>
            </svg>
        </button>

        <button type="button"
             v-if="canShowSettingsCustomize"
             :class="{'live-edit-right-sidebar-active': buttonIsActiveQuickEdit }"
             class="btn-icon live-edit-toolbar-buttons mw-toolbar-icon-btn"
             aria-label="Quick AI edit"
             title="Quick AI edit"
             data-mw-label="AI Edit"
             data-mw-sidebar-priority="secondary"
             :aria-pressed="buttonIsActiveQuickEdit"
             v-on:click="handleQuickEdit()"
             v-on:keydown.enter.prevent="handleQuickEdit()"
             v-on:keydown.space.prevent="handleQuickEdit()">
            <v-tooltip activator="parent" location="start">
                <Lang>Quick AI edit</Lang>
            </v-tooltip>
            <svg viewBox="0 0 24 24" width="22px" height="22px" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </button>


        <div>
            <!--            <CurrentNodeTextEditButton></CurrentNodeTextEditButton>
                        <CurrentNodeModuleEditButton></CurrentNodeModuleEditButton>
                        <CurrentLayoutSettingsButtons></CurrentLayoutSettingsButtons>-->
            <CurrentContextMenu></CurrentContextMenu>
        </div>


        <!-- task-2026-09-28 — the "More" (Advanced) right-rail button and its
             teleported ToolsButtons panel were removed. Every action it exposed
             (Setup wizard, Code editor, Reset content, Layers, Layout settings,
             Clear cache) already lives in the toolbar 3-dots menu
             (ToolbarToolsDropdown.vue), so the rail keeps only the primary
             surfaces (Templates & layouts / Element styles / Quick AI edit /
             context actions) and the 3-dots is the single overflow menu. -->

    </div>
</template>


<script>


import ToolbarMulilanguageSelector from "./ToolbarMulilanguageSelector.vue";
import CurrentLayoutSettingsButtons from "../ContextMenu/CurrentLayoutSettingsButtons.vue";
import CurrentContextMenu from "../ContextMenu/CurrentContextMenu.vue";
import CurrentNodeTextEditButton from "../ContextMenu/CurrentNodeTextEditButton.vue";
import CurrentNodeModuleEditButton from "../ContextMenu/CurrentNodeModuleEditButton.vue";
import CSSGUIService from "../../../api-core/services/services/css-gui.service.js";


export default {
    components: {
        ToolbarMulilanguageSelector,
        CurrentContextMenu,
        CurrentNodeTextEditButton,
        CurrentNodeModuleEditButton,
        CurrentLayoutSettingsButtons,

    },
    props: {
        template: {
            type: String,
            default: 'default',
        },
    },
    methods: {
        handleInsertLayout: function () {
            let active = mw.top().app.liveEdit.layoutHandle.getTarget();
            const selector = '.edit[data-layout-container] .module-layouts';

            if(!active) {

                //get layout target from element
                var activeElement = mw.top().app.liveEdit.elementHandle.getTarget();

                if (activeElement) {
                    var layoutTarget = mw.tools.firstParentOrCurrentWithAnyOfClasses(activeElement, ['module-layouts', 'edit']);
                    if (layoutTarget) {
                        active = layoutTarget;
                    }
                }
            }

            if (!active || !active.matches(selector)) {
                const doc = mw.top().app.canvas.getDocument();
                const scrollCenter = doc.defaultView.scrollY + (doc.defaultView.innerHeight / 2);
                const docHeight = Math.max(doc.documentElement.clientHeight, doc.defaultView.innerHeight);

                const arr = Array.from(doc.querySelectorAll(selector))
                const activeIndex = arr
                    .map(node => {
                        const rect = node.getBoundingClientRect();
                        const rectTop = rect.top + doc.defaultView.scrollY;

                        return {
                            top: rectTop,
                            visible: !(rect.bottom < 0 || rectTop - docHeight >= 0),
                            node,
                        };
                    })
                    .reduce(function (prev, curr, index) {

                        return (curr.visible && Math.abs(curr.top - scrollCenter) < Math.abs(prev - scrollCenter) ? index : prev);
                    }, -1);

                if (activeIndex >= 0) {
                    mw.top().app.liveEdit.layoutHandle.set(arr[activeIndex])
                    active = mw.top().app.liveEdit.layoutHandle.getTarget();

                }

            }


            if (active) {
                active.scrollIntoView();
                mw.top().app.editor.dispatch('insertLayoutRequestOnBottom', active)
            }

        },
        handleQuickEdit: function () {
            // task-2026-11-07 — the right-rail AI button is now the SINGLE AI
            // entry point and opens the CHAT (primary), replicating the docked
            // AI chat sidebar. The classic field editor ("Quick edit") is reached
            // from the dropdown in the chat sidebar header, not from here.
            let contentId = 0;
            try {
                const led = mw.top().app.canvas.getLiveEditData();
                contentId = (led && led.content && led.content.id) ? led.content.id : 0;
            } catch (e) {}
            try {
                window.dispatchEvent(new CustomEvent('mw:ai-chat:toggle', { detail: {
                    agentType: 'liveedit', contentId: contentId, canvas: true, title: mw.lang('AI Assistant'),
                } }));
            } catch (e) {}
        },
        handleAdmin() {
            // task-2026-09-05-adminrail — toggle the Filament admin sidebar
            // (aside.fi-sidebar) from the right rail. This is the single
            // entry point after the left drawer was merged into the right
            // sidebar. Close any other right-rail panel first so the admin
            // nav doesn't stack over the ESE / Template-settings / Advanced /
            // Quick-AI panels — one sidebar surface visible at a time.
            this.emitter.emit('live-edit-ui-show', 'admin-sidebar');
            mw.app.advancedPanelWidget?.hide();
            mw.top().app.templateSettingsWidget?.hide();
            CSSGUIService.hide();
            mw.top().app.liveEditWidgets.toggleAdminSidebar();
        },
        show: function (name) {

            this.emitter.emit('live-edit-ui-show', name);
            this.$refs.moreSettingsDropdown?.classList.remove('show');
            mw.app.advancedPanelWidget?.hide();
            // task-2026-09-05-adminrail — single-sidebar mutual exclusion on
            // the global-event path too (MainDrawer "Theme settings",
            // mw.open-template-settings): close the admin drawer so a second
            // right surface never opens on top of it.
            mw.top().app.liveEditWidgets?.closeAdminSidebar();
        },
        toggle: function (name) {
            // task-2026-05-29-eaf3a1 / AI-1157 — right-rail button path must emit
            // live-edit-ui-show so RightSidebar.showSidebar flips true and the
            // Vue Teleport source <TemplateSettingsTeleport> mounts; the global
            // event path (mounted() handler line below) already routes through
            // show() which carries the emit. Without this emit the controlBox
            // opens but its body stays empty on the 'template-settings' path.
            this.emitter.emit('live-edit-ui-show', name);

            this.$refs.moreSettingsDropdown?.classList.remove('show');
            mw.app.advancedPanelWidget?.hide();
            // task-2026-09-05-adminrail — single-sidebar mutual exclusion:
            // opening Templates & layouts / Element styles closes the admin
            // drawer so only one right sidebar surface shows at a time.
            mw.top().app.liveEditWidgets?.closeAdminSidebar();

            if (name !== 'style-editor') {
                CSSGUIService.hide()
            }
            if (name === 'template-settings') {

                mw.top().app.templateSettingsWidget.toggle()

            } else if (name === 'style-editor') {

                CSSGUIService.toggle()

            }

        },

        hideMoreSettingsDropdown() {
            this.$refs.moreSettingsDropdown?.classList.remove('show');
            mw.app.advancedPanelWidget?.hide();
        },

        openReportIssueModal() {

            var url = 'https://microweber.org/go/feedback/';
            let linkInModal = mw.top().dialogIframe({
                url: url,
                width: 900,
                height: 900,
                closeOnEscape: true,

            });
            linkInModal.dialogContainer.style.paddingLeft = '0px';
            linkInModal.dialogContainer.style.paddingRight = '0px';
            linkInModal.dialogFooter.style.display = 'none';


        }
    },

    unmounted() {},
    mounted() {
        this.emitter.on("live-edit-ui-show", () => {
            this.hideMoreSettingsDropdown();
        });
        this.emitter.on("show-content-reset", () => {
            this.hideMoreSettingsDropdown();
        });

        this.emitter.on("show-code-editor", () => {
            this.hideMoreSettingsDropdown();
        });

        window.mw.app.canvas.on('liveEditCanvasLoaded', () => {
            this.hideMoreSettingsDropdown();
        });


        mw.top().app.on('moduleInserted', () => {
            this.hideMoreSettingsDropdown();
        })
        mw.top().app.on('layoutCloned', () => {
            this.hideMoreSettingsDropdown();
        })

        mw.top().app.on('mw.open-template-settings', () => {
            // close the hamburger
            if (document.getElementById('user-menu-wrapper')) {
                document.getElementById('user-menu-wrapper').classList.remove('active');
            }

            this.hideMoreSettingsDropdown();

            this.show('template-settings')
        });

        mw.top().app.on('mw.open-report-issue-modal', () => {
            this.openReportIssueModal();
        });

        mw.top().app.on('showSetupWizard', () => {
            this.hideMoreSettingsDropdown();
        });

        mw.app.canvas.on('canvasDocumentClick', event => {
            this.hideMoreSettingsDropdown();

        });

        mw.app.canvas.on('liveEditCanvasBeforeUnload', event => {
            this.hideMoreSettingsDropdown();
        });


        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.editor.on('onModuleSettingsRequest', event => {
                this.hideMoreSettingsDropdown();
            });

            mw.app.editor.on('onLayoutSettingsRequest', event => {
                this.hideMoreSettingsDropdown();
            });
            mw.app.editor.on('insertLayoutRequest', event => {
                this.hideMoreSettingsDropdown();
            });
            mw.app.editor.on('insertModuleRequest', event => {
                this.hideMoreSettingsDropdown();
            });


            if (mw.top().app.liveEditWidgets) {
                // Layers are now handled in ToolsButtons component
            }


            this.insertLayoutVisible = !!mw.app.canvas.getDocument().querySelector('.edit[data-layout-container]')


        });

        let instance = this;
        this.emitter.on("live-edit-ui-show", (show) => {


            if (show == 'template-settings') {
                instance.buttonIsActive = true;
                instance.buttonIsActiveStyleEditor = false;
            } else if (show == 'style-editor') {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = true;
            } else if (show == 'template-settings-close') {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = false;
            } else {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = false;

            }


        });


        mw.top().app.templateSettingsWidget.on('show', () => {
            this.buttonIsActive = true;
            mw.top().app.liveEditWidgets.closeQuickEditComponent();
        });
        mw.top().app.templateSettingsWidget.on('hide', () => this.buttonIsActive = false);

        mw.top().app.guiEditorBox.on('show', () => {
            this.buttonIsActiveStyleEditor = true;
            mw.top().app.liveEditWidgets.closeQuickEditComponent()
        });
        mw.top().app.guiEditorBox.on('hide', () => this.buttonIsActiveStyleEditor = false);

        mw.top().app.readyPromise(() => {
            mw.top().app.liveEditWidgets.on('openQuickEditComponent', () => {
                this.buttonIsActiveQuickEdit = true
            })

            mw.top().app.liveEditWidgets.on('closeQuickEditComponent', () => {
                this.buttonIsActiveQuickEdit = false
            })

            // task-2026-09-05-adminrail — reflect the admin sidebar open
            // state on the rail Admin button. The widget dispatches these
            // on open/close (incl. when closeAll() closes it because
            // another panel opened), so the button highlight stays in sync.
            mw.top().app.liveEditWidgets.on('adminSidebarOpen', () => {
                this.buttonIsActiveAdmin = true
            })
            mw.top().app.liveEditWidgets.on('adminSidebarClose', () => {
                this.buttonIsActiveAdmin = false
            })

        });

        mw.top().app.canvas.on('liveEditCanvasLoaded', () => {
            this.canShowSettingsCustomize = !!mw.top().app.canvas.getLiveEditData()
        });

        // task-2026-11-07 — the AI rail button opens the docked chat sidebar, so
        // its active highlight now follows the chat's open/close (not the old
        // quick-edit panel). The quick-edit listeners above stay for when the
        // field editor is opened from the chat header dropdown.
        try {
            window.addEventListener('mw:ai-chat:opened', () => { this.buttonIsActiveQuickEdit = true; });
            window.addEventListener('mw:ai-chat:closed', () => { this.buttonIsActiveQuickEdit = false; });
        } catch (e) {}
    },
    data() {

        var canShowSettingsCustomize = !!mw.top().app.canvas.getLiveEditData();

        return {
            canShowSettingsCustomize: canShowSettingsCustomize,
            buttonIsActive: false,
            buttonIsActiveStyleEditor: false,
            buttonIsActiveQuickEdit: false,
            buttonIsActiveAdmin: false,      // task-2026-09-05-adminrail — admin sidebar open state
            insertLayoutVisible: false,
            iconInsertlayout: mw.top().app?.iconService?.icon('add-layout'),
        }
    },

}
</script>

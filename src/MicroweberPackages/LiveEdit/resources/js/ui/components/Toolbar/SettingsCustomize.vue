<template>





    <div class="mw-live-edit-right-sidebar-wrapper me-2">
        <span v-on:click="toggle('template-settings')" :class="{'live-edit-right-sidebar-active': buttonIsActive && !buttonIsActiveStyleEditor }"
              class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-css-editor-toggle">


            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22"><path
                d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/></svg>
        </span>

        <div v-on:click="toggle('style-editor')" :class="{'live-edit-right-sidebar-active': !buttonIsActive && buttonIsActiveStyleEditor }"
             class="btn-icon live-edit-toolbar-buttons live-edit-toolbar-button-css-editor-toggle">
            <svg class="me-1" fill="currentColor"
                 xmlns="http://www.w3.org/2000/svg" height="22"
                 viewBox="0 -960 960 960" width="22">
                <path
                    d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"/>
            </svg>
        </div>

        <div class="dropdown btn-icon live-edit-toolbar-buttons">
            <a role="button" id="dropdownLiveEditMenuLinkMoreSettings" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path
                        d="M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"/>
                </svg>
            </a>

            <div class="dropdown-menu mw-live-edit-tools-dropdown-menu"
                 aria-labelledby="dropdownLiveEditMenuLinkMoreSettings" ref="moreSettingsDropdown">
                <ToolsButtons></ToolsButtons>
            </div>
        </div>


    </div>
</template>


<script>


import ToolsButtons from "../RightSidebar/ToolsButtons.vue";
import ToolbarMulilanguageSelector from "./ToolbarMulilanguageSelector.vue";
import CSSGUIService from "../../../api-core/services/services/css-gui.service.js";

import {tr} from "vuetify/locale";

export default {
    components: {
        ToolsButtons,
        ToolbarMulilanguageSelector,

    },
    methods: {
        show: function (name) {

            this.emitter.emit('live-edit-ui-show', name);
            this.$refs.moreSettingsDropdown.classList.remove('show');
        },
        toggle: function (name) {

            this.$refs.moreSettingsDropdown.classList.remove('show');

            if(name === 'template-settings') {
                if(!this.buttonIsActive){
                    this.buttonIsActive = true;
                    this.buttonIsActiveStyleEditor = false;
                    this.emitter.emit('live-edit-ui-show', name);
                    CSSGUIService.show()
                } else {
                    this.buttonIsActive = false;
                    CSSGUIService.hide()
                }

            } else if(name === 'style-editor') {
              if(this.buttonIsActiveStyleEditor){
                  this.buttonIsActiveStyleEditor = false;
                  CSSGUIService.hide()
              } else {
                  this.buttonIsActiveStyleEditor = true;
                  this.buttonIsActive = false;
                  this.emitter.emit('live-edit-ui-show', name);
                  CSSGUIService.show()
              }
            }

        },

        hideMoreSettingsDropdown() {
            this.$refs.moreSettingsDropdown.classList.remove('show');
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

    mounted() {

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
        });

        let instance = this;
        this.emitter.on("live-edit-ui-show", (show) => {


            if (show == 'template-settings') {
                instance.buttonIsActive = true;
                instance.buttonIsActiveStyleEditor = false;
            } else if (show == 'style-editor') {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = true;
            } else {
                instance.buttonIsActive = false;
                instance.buttonIsActiveStyleEditor = false;

            }



        });
    },
    data() {
        return {
            buttonIsActive: false,
            buttonIsActiveStyleEditor: false
        }
    }
}
</script>

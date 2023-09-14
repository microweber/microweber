<template>
    <div>

        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">


            <div class="d-flex align-items-center justify-content-between px-3 pt-4 pb-0 position-relative">
                <span v-on:click="show('template-settings')" :class="[buttonIsActive?'live-edit-right-sidebar-active':'']" class="mdi mdi-close x-close-modal-link" style="top: 17px;"></span>
                <div id="rightSidebarTabStyleEditorNav" role="tablist">
                    <a class="mw-admin-action-links mw-adm-liveedit-tabs active me-3" data-bs-toggle="tab"
                       data-bs-target="#style-edit-global-template-settings-holder" type="button" role="tab">
                        Template Styles
                    </a>


                    <a class="mw-admin-action-links mw-adm-liveedit-tabs" data-bs-toggle="tab"
                       data-bs-target="#style-edit-custom-template-settings-holder" type="button" role="tab">
                        Tools
                    </a>
                </div>

                <div v-if="showSidebar" class="mb-2">
                    <span v-on:click="closeSidebar" class="cursor-pointer">
                       <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m249-207-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg>

                    </span>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-settings-holder"
                     role="tabpanel">

                    <iframe :src="'/api/template/template-settings-sidebar'" style="width:100%;height:100vh;">

                    </iframe>

                </div>
                <div class="tab-pane tab-pane-slide-right" id="style-edit-custom-template-settings-holder"
                     role="tabpanel">

                    <ToolsButtons></ToolsButtons>


                </div>

            </div>
        </div>

    </div>
</template>

<style>
.general-theme-settings {
    background: #000;

}

.live-edit-gui-editor-opened #live-editor-frame{
    margin-right: 303px;
}
</style>

<script>
import TemplateSettings from "./TemplateSettings/TemplateSettings.vue";
import Editor from "../Toolbar/Editor.vue";
import ToolsButtons from  "./ToolsButtons.vue";

import  CSSGUIService from "../../../api-core/services/services/css-gui.service.js";

export default {
    components: {
        Editor,
        ToolsButtons,
        TemplateSettings,
    },
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);

            console.log(CSSGUIService)
            CSSGUIService.toggle()
            // this.emitter.emit('live-edit-ui-show', name);
        },
        closeSidebar() {

            CSSGUIService.show()
        },
        openSidebar() {

            CSSGUIService.close()
        }
    },
    mounted() {
        const instance = this;

        var firstTabEl = document.querySelector('#rightSidebarTabStyleEditorNav li:first-child a')
        if(firstTabEl !== null){
            var firstTab = new bootstrap.Tab(firstTabEl)
            firstTab.show()
        }

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'template-settings') {
                if (instance.buttonIsActive == false) {
                    instance.buttonIsActive = true;
                } else {
                    instance.buttonIsActive = false;
                }
            }
        });

    },
    data() {
        return {
            showSidebar: false,
            buttonIsActive: false

        }
    }
}
</script>


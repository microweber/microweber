<template>
    <div>




        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">



            <div v-show="showElementStyleEditor">



                <StyleEditor></StyleEditor>
            </div>




            <div v-show="showTemplateSettings">
                <div class="d-flex align-items-center justify-content-between px-3 pt-4 pb-0 position-relative">
                    <span v-on:click="closeSidebar" :class="[buttonIsActive?'live-edit-right-sidebar-active':'']" class="mdi mdi-close x-close-modal-link" style="top: 17px;"></span>
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


                </div>

                <div class="tab-content">
                    <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-settings-holder"
                         role="tabpanel">
                        <div v-if="showTemplateSettings" >

                        <iframe :src="buildIframeUrlTemplateSettings()" style="width:100%;height:100vh;"
                                frameborder="0"
                                allowfullscreen></iframe>

                        </div>


                    </div>
                    <div class="tab-pane tab-pane-slide-right" id="style-edit-custom-template-settings-holder"
                         role="tabpanel">

                        <ToolsButtons></ToolsButtons>


                    </div>

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
import StyleEditor from "../StyleEditor/StyleEditor.vue";

import  CSSGUIService from "../../../api-core/services/services/css-gui.service.js";

export default {
    components: {

        StyleEditor,
        Editor,
        ToolsButtons,
        TemplateSettings,
    },
    methods: {
        show: function (name) {
            this.showSidebar = true;
            CSSGUIService.show();
        },
        closeSidebar() {
            // swith tab to template settings
            this.showTemplateSettings = true;
            this.showSidebar = false;
            this.showElementStyleEditor = false;
            CSSGUIService.hide();
        },

        openSidebar() {
            this.showTemplateSettings = true;
            this.showSidebar = true;
            this.showElementStyleEditor = false;
            CSSGUIService.show();
        },
        buildIframeUrlTemplateSettings: function (url) {

            var moduleType = 'editor/sidebar_template_settings';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_sidebar_template_settings';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;

            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            return src;

        }
    },
    mounted() {

        const rightSidebarInstance = this;


        mw.app.canvas.on('liveEditCanvasLoaded', function () {
            rightSidebarInstance.showTemplateSettings = true;
        });

        mw.app.canvas.on('liveEditCanvasBeforeUnload', function () {
          rightSidebarInstance.showTemplateSettings = false;
        });

        this.emitter.on("live-edit-ui-show", show => {

            rightSidebarInstance.showTemplateSettings = false;
            rightSidebarInstance.showElementStyleEditor = false;

            if (show == 'template-settings') {
                rightSidebarInstance.buttonIsActive = true;
                rightSidebarInstance.showTemplateSettings = true;
                rightSidebarInstance.showElementStyleEditor = false;

            } else if(show == 'style-editor') {

                rightSidebarInstance.showTemplateSettings = false;
                rightSidebarInstance.showElementStyleEditor = true;
                rightSidebarInstance.showSidebar = true;
                rightSidebarInstance.buttonIsActive = false;
            } else {
                rightSidebarInstance.showTemplateSettings = false;
                rightSidebarInstance.showElementStyleEditor = false;
                rightSidebarInstance.showSidebar = false;
                rightSidebarInstance.buttonIsActive = false;
            }

        });

    },
    data() {
        return {
            showSidebar: false,
            showTemplateSettings: false,
            buttonIsActive: false,
            showElementStyleEditor: false
        }
    }
}
</script>


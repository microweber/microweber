<template>
    <div>

        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">
            <div v-if="showSidebar" class="text-end my-3 me-1">
                <span v-on:click="closeSidebar" class="cursor-pointer">
                   <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m249-207-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg>

                </span>
            </div>

            <div>
                <ul class="nav nav-pills nav-justified" id="rightSidebarTabStyleEditorNav" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#style-edit-global-template-settings-holder" type="button" role="tab">
                            Template Styles
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#style-edit-custom-template-settings-holder" type="button" role="tab">
                            Tools
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-settings-holder"
                         role="tabpanel">

                        <TemplateSettings></TemplateSettings>

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
</style>

<script>
import TemplateSettings from "./TemplateSettings/TemplateSettings.vue";
import Editor from "../Toolbar/Editor.vue";
import ToolsButtons from  "./ToolsButtons.vue";

export default {
    components: {
        Editor,
        ToolsButtons,
        TemplateSettings,
    },
    methods: {

        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        },

        closeSidebar() {
            this.showSidebar = false;
            document.getElementById('live-edit-frame-holder')
                .removeAttribute('style');
        },
        openSidebar() {
            this.showSidebar = true;
            document.getElementById('live-edit-frame-holder')
                .setAttribute('style', 'margin-right: 303px;');
        }
    },
    mounted() {
        const instance = this;

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'template-settings') {
                if (instance.showSidebar == false) {
                    instance.openSidebar();
                } else {
                    instance.closeSidebar();
                }
            }
        });
        var firstTabEl = document.querySelector('#rightSidebarTabStyleEditorNav li:first-child button')
        var firstTab = new bootstrap.Tab(firstTabEl)

        firstTab.show()
        // Close on Escape
        document.addEventListener('keyup', function (evt) {
            if (evt.keyCode === 27) {
                instance.showSidebar = false;
            }
        });
    },
    data() {
        return {
            showSidebar: false
        }
    }
}
</script>


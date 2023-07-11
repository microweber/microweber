<template>
    <div>

        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">

            <div v-if="showSidebar">
                <button v-on:click="closeSidebar" type="button" class="btn btn-danger">
                    Close Sidebar
                </button>
            </div>




           <div>
               <ul class="nav nav-pills nav-justified" id="rightSidebarTabStyleEditorNav" role="tablist">
                   <li class="nav-item" role="presentation">
                       <button class="nav-link active"  data-bs-toggle="tab" data-bs-target="#style-edit-global-template-settings-holder" type="button" role="tab" >Global Styles</button>
                   </li>
                   <li class="nav-item" role="presentation">
                       <button class="nav-link"   data-bs-toggle="tab" data-bs-target="#style-edit-custom-template-settings-holder" type="button" role="tab" >Custom Styles</button>
                   </li>
               </ul>

               <div class="tab-content">
                   <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-settings-holder" role="tabpanel">

                       <TemplateSettings></TemplateSettings>

                   </div>
                   <div class="tab-pane tab-pane-slide-right" id="style-edit-custom-template-settings-holder" role="tabpanel">
                       <StyleEditor></StyleEditor>
                   </div>

               </div>
           </div>



        </div>

    </div>
</template>

<style>
.general-theme-settings {
    background:#000;
}
</style>

<script>
import TemplateSettings from "./TemplateSettings/TemplateSettings.vue";
import StyleEditor from "./StyleEditor/StyleEditor.vue";

export default {
    components: {
        TemplateSettings,
        StyleEditor
    },
    methods: {
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


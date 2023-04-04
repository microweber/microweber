<template>
    <div>

        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">

            <div v-if="showSidebar">
                <button v-on:click="closeSidebar" type="button" class="btn btn-danger">
                    Close Sidebar
                </button>
            </div>

            <TemplateSettings></TemplateSettings>
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
export default {
    components: {
        TemplateSettings
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
                .setAttribute('style', 'margin-right: 301px;');
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


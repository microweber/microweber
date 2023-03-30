<template>
    <div>

        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">

            <div v-if="showSidebar">
                <button v-on:click="showSidebar = false" type="button" class="btn btn-danger">
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
    mounted() {
        const instance = this;

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'template-settings') {
                if (instance.showSidebar == false) {
                    instance.showSidebar = true;
                } else {
                    instance.showSidebar = false;
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


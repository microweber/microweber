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
        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'template-settings') {
                if (this.showSidebar == false) {
                    this.showSidebar = true;
                } else {
                    this.showSidebar = false;
                }
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


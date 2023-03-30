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

<template>
    <div>
        <div id="general-theme-settings" :class="[showSidebar == true ? 'active' : '']">
            <TemplateSettings></TemplateSettings>
        </div>
    </div>
</template>

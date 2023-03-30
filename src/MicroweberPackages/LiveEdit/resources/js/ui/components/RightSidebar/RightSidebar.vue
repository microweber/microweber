<script>
import TemplateSettings from "./TemplateSettings/TemplateSettings.vue";
export default {
    components: {
        TemplateSettings
    },
    emits: {
        liveEditUiShow: function (name) {
           alert(name);
        }
    },
    mounted() {

    },
    data() {
        return {
            showSidebar: true
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

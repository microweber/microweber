<template>


    <div id="bubble-nav" class="active">
         <span data-bs-toggle="tooltip" aria-label="Show layouts" data-bs-original-title="Show layouts" v-on:click="show('show-layouts')">
            <LayoutsIcon />
        </span>

        <span data-bs-toggle="tooltip" aria-label="Show modules" data-bs-original-title="Show modules" v-on:click="show('show-modules')">
            <ModulesIcon />
        </span>
    </div>

</template>
<script>
import { PencilIcon, PlayIcon, CogIcon, LightBulbIcon } from '@heroicons/vue/outline'
import LayoutsIcon from "../Icons/LayoutsIcon.vue";
import ListIcon from "../Icons/ListIcon.vue";
import ModulesIcon from "../Icons/ModulesIcon.vue";
import DesignSettingsIcon from "../Icons/DesignSettingsIcon.vue";

export default {
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        }
    },
    components: {
        DesignSettingsIcon,
        LayoutsIcon,
        ModulesIcon,
        PencilIcon,
        PlayIcon,
        CogIcon
    },
    data() {
        return {

        }
    }
}
</script>

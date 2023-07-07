<template>


    <div id="bubble-nav" class="active">
         <span data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Layouts" data-bs-original-title="Layouts" v-on:click="show('show-layouts')">
            <LayoutsIcon />
        </span>

        <span data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Modules" data-bs-original-title="Modules" v-on:click="show('show-modules')">
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
    },
    mounted() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
}
</script>

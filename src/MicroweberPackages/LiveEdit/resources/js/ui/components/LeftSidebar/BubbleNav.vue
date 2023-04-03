<template>


    <div id="bubble-nav" class="active">
        <span v-on:click="show('template-settings')">
            <CogIcon class="h-6 w-6 text-blue-500"/>
        </span>
        <span v-on:click="show('show-modules')">
            <PlayIcon class="h-6 w-6 text-blue-500"/>
        </span>
        <span v-on:click="show('show-layouts')">
            <LayoutsIcon class="h-6 w-6" />
        </span>
    </div>

</template>
<script>
import { PencilIcon, PlayIcon, CogIcon, LightBulbIcon } from '@heroicons/vue/outline'
import LayoutsIcon from "../Icons/LayoutsIcon.vue";
import ListIcon from "../Icons/ListIcon.vue";

export default {
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        }
    },
    components: {
        LayoutsIcon,
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

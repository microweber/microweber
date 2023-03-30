<template>


    <div id="bubble-nav" class="active">
        <span v-on:click="show('template-settings')">
            <CogIcon class="h-6 w-6 text-blue-500"/>
        </span>
        <span v-on:click="show('show-modules')">
            <PlayIcon class="h-6 w-6 text-blue-500"/>
        </span>
        <span v-on:click="show('show-layouts')">
           <PencilIcon class="h-6 w-6 text-blue-500"/>
        </span>
    </div>

</template>
<script>
import { ViewListIcon } from '@heroicons/vue/solid'
import { PencilIcon, PlayIcon, CogIcon } from '@heroicons/vue/outline'

export default {
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        }
    },
    components: {
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

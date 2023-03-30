<template>

    <div id="bubble-nav" class="active">
        <span>wow!</span>
        <span v-on:click="show('template-settings')">
           TS
        </span>
    </div>

</template>
<script>
import { EventBus } from '../../event-bus.js';
export default {
    methods: {
        show: function (name) {
            EventBus.$emit('liveEditUiShow', name);
        }
    },
    components: {

    },
    data() {
        return {

        }
    }
}
</script>

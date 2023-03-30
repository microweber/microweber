<template>

    <div id="bubble-nav" class="active">
        <span>wow!</span>
        <span v-on:click="show('template-settings')">
           TS
        </span>
    </div>

</template>
<script>
export default {
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
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

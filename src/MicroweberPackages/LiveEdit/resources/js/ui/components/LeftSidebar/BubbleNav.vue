<template>

    <div id="bubble-nav" class="active">
        <span v-on:click="show('template-settings')">
           TS
        </span>
        <span v-on:click="show('show-modules')">
           Mod
        </span>
        <span v-on:click="show('show-layouts')">
           Layo
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

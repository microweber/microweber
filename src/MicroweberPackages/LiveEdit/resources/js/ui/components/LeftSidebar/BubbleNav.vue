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
            this.$emit('liveEditUiShow', name);
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

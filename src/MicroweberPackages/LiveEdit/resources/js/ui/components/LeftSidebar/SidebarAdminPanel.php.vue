<template>
   todo - add this
</template>


<script>

export default {
    components: {

    },
    methods: {

        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        },


    },
    mounted() {

    },
    data() {
        return {
            showSidebar: false
        }
    }
}
</script>


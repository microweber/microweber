<template>

    <VueDragResize :isActive="true" :w="200" :h="200" v-on:resizing="resize" v-on:dragging="resize">
       <h3>Hello World!</h3>
        <p>{{ top }} х {{ left }} </p>
        <p>{{ width }} х {{ height }}</p>>
    </VueDragResize>

</template>

<script>

export default {

    mounted() {
        mw.top().app.on('openElementSettingsModal', (data) => {
            this.openModal();
        });
    },


    data() {
        return {
            width: 0,
            height: 0,
            top: 0,
            left: 0
        }
    },

    methods: {

        openModal() {
          alert(3333);
        },


    }
}
</script>

<template>

   <div id="elementSettingsModal" style="display: none">


       tyka e modala

   </div>

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

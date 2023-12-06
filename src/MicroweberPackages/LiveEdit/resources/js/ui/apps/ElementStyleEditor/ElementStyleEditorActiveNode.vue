<script>


export default {

    mounted() {
        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
            console.log('mw.elementStyleEditor.selectNode', element);
              this.$root.selectedElement = element;
        });

        mw.top().app.on('mw.elementStyleEditor.refreshNode', (element) => {
              this.$root.selectedElement = null;
              this.$root.selectedElement = element;
        });


    },


}


</script>


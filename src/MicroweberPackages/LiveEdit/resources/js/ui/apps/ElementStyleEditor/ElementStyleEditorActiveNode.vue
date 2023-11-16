<script>


export default {

    mounted() {
        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
              this.$root.selectedElement = element;
        });


    },


}


</script>


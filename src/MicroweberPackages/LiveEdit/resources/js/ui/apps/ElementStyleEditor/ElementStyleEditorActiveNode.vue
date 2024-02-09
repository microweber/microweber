<script>


export default {

    mounted() {
        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
            this.$root.selectedLayout = null;
            this.$root.selectedElement = null;

            this.$root.selectedElement = element;
        });

        mw.top().app.on('mw.elementStyleEditor.refreshNode', (element) => {
            this.$root.selectedElement = null;
            this.$root.selectedLayout = null;
            this.$root.selectedElement = element;
        });


        mw.top().app.on('mw.elementStyleEditor.selectLayout', (element) => {
            this.$root.selectedLayout = null;
            this.$root.selectedLayout = element;

        });

        mw.top().app.canvas.on('reloadCustomCssDone', () => {

            setTimeout(() => {

                var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
                 if(activeElement) {
                    this.$root.selectedLayout = null;
                    this.$root.selectedLayout = activeElement;
                }


            },1000 );


        });





    },


}


</script>


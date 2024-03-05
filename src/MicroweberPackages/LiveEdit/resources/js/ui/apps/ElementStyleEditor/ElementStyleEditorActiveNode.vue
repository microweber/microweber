<script>


export default {
    methods: {


    },
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
                     this.$root.selectedElement = null;

                     this.$root.selectedElement = activeElement;
                }


            },300 );


        });


        mw.top().app.canvas.on('canvasDocumentClick', function (event) {

                    var activeNodeSelected = mw.top().app.liveEdit.getSelectedNode();
                    var targetWindow = mw.top().app.canvas.getWindow();

                    var can = true;
                    var activeNode = event.target;
                    //
                    // var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
                    // var activeModule = mw.top().app.liveEdit.handles.get('module').getTarget();
                    // var activeLayout = mw.top().app.liveEdit.handles.get('layout').getTarget();
                    // if(activeElement){
                    //     activeNode = activeElement;
                    // } else if(activeModule){
                    //     activeNode = activeModule;
                    // } /*else if(activeLayout){
                    //     activeNode = activeLayout;
                    // } */ else {
                    //
                    // }
                    activeNode = event.target;

                    if(activeNode && !activeNode.id){
                        try {
                            targetWindow.mw.tools.generateSelectorForNode(activeNode);
                        } catch (e) {

                        }
                    }

                    if(activeNode && activeNode.id){
                        can = true;
                    } else {
                        can = false;
                    }


                    //   var can = mw.top().app.liveEdit.canBeElement(activeNode)
                    if (can) {
                        //check if has Id
                        if (activeNode) {
                            var id = activeNode.id;
                            if (!id) {
                                // try
                                var id = targetWindow.mw.tools.generateSelectorForNode(activeNode);


                                try {

                                } catch (e) {
                                    // console.log(e);
                                }

                                //   activeNode.id = id;
                            }
                            mw.top().app.dispatch('mw.elementStyleEditor.selectNode', activeNode);
                        }
                        // var event = new CustomEvent('refreshSelectedElement')
                        // if(styleEditorInstance.cssEditorIframe.contentWindow) {
                        //     styleEditorInstance.cssEditorIframe.contentWindow.document.dispatchEvent(event);
                        // }
                    } else {
                        mw.top().app.dispatch('mw.elementStyleEditor.selectNode', null);
                        var targetLayout = mw.top().app.liveEdit.liveEditHelpers.targetIsInLayout(activeNode)
                        if(targetLayout){
                            mw.top().app.dispatch('mw.elementStyleEditor.selectLayout', targetLayout);
                        }

                    }
        });

    },

}


</script>


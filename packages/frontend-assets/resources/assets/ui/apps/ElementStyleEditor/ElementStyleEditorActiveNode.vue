<template>

</template>

<script>


export default {
    methods: {


    },

    data() {
        return {


        }
    },
    mounted() {
        // task-2026-05-05-dc910e — register listeners EAGERLY,
        // not gated by `onLiveEditReady`. Live-edit boots before
        // the user clicks "Design"; by the time this Vue component
        // mounts, `onLiveEditReady` has already fired (Microweber's
        // event bus does not replay missed events), so the inner
        // listeners would never get attached and `selectedElement`
        // would stay `null` forever — leaving the user staring at
        // "Please select an element to edit" no matter how many
        // canvas elements they clicked. The listeners are
        // idempotent and parent `mw.top().app` is available as
        // soon as the iframe-page mounts the Vue app, so there's
        // no reason to defer.
        const root = this;

        const setupListeners = () => {
            mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
                root.$root.selectedLayout = null;
                root.$root.selectedElement = null;

                root.$root.selectedElement = element;
            });

            mw.top().app.on('mw.elementStyleEditor.refreshNode', (element) => {
                root.$root.selectedElement = null;
                root.$root.selectedLayout = null;
                root.$root.selectedElement = element;
            });


            mw.top().app.on('mw.elementStyleEditor.selectLayout', (element) => {
                root.$root.selectedLayout = null;
                root.$root.selectedLayout = element;

            });

            mw.top().app.canvas.on('reloadCustomCssDone', () => {

                setTimeout(() => {

                    var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
                    if(activeElement) {

                        root.$root.selectedLayout = null;
                        root.$root.selectedElement = null;

                        root.$root.selectedElement = activeElement;
                    }


                },300 );


            });


            mw.top().app.canvas.on('canvasDocumentClick', function (event) {

                var activeNodeSelected = mw.top().app.liveEdit.getSelectedNode();
                var targetWindow = mw.top().app.canvas.getWindow();

                var activeNode = event.target;

                if(activeNode && activeNode.getAttribute('data-mw-free-element')){
                    // check if has child
                    var hasChild = activeNode.firstElementChild;
                    if(hasChild){
                        activeNode = hasChild;
                    }
                }


                var can = true;
                // var activeNode = event.target;
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
        };

        // Try immediately; if mw.top().app.liveEdit isn't fully
        // wired yet (rare race during cold boot), retry once
        // after the next macrotask. The listeners are idempotent
        // so a duplicate registration is harmless.
        try {
            if (mw && mw.top && mw.top() && mw.top().app && mw.top().app.canvas) {
                setupListeners();
            } else {
                setTimeout(setupListeners, 200);
            }
        } catch (e) {
            setTimeout(setupListeners, 200);
        }

        // Also seed `selectedElement` from the current selection
        // if the user already had something highlighted before
        // opening the Style Editor — covers the case where
        // selection happened before the Vue component mounted.
        try {
            const existing = mw.top().app.liveEdit.getSelectedNode();
            if (existing) {
                this.$root.selectedElement = existing;
            }
        } catch (e) { /* live-edit not yet ready; selection will arrive via the listener */ }
    },

}


</script>


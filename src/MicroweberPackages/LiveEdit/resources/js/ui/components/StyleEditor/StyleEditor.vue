<template>
    <div id="mw-css-editor-sidebar-iframe-holder"></div>
</template>


<script>
export default {
    methods: {},
    data() {
        return {
            cssEditorIframe: null
        }
    },
    mounted() {
        var instance = this;
        mw.app.canvas.on('canvasDocumentClick',function(){
            if(instance.cssEditorIframe) {
                var selected = mw.app.liveEdit.elementHandle.getTarget();
                if (selected) {
                    instance.cssEditorIframe.contentWindow.selectNode(selected)
                }
            }
        });
        mw.app.canvas.on('liveEditCanvasLoaded', function () {
            if(instance) {
                instance.cssEditorIframe = mw.liveEditWidgets.cssEditorInSidebarAccordion();
                instance.cssEditorIframe.onload = function () {
                };
            }
        });
    }

}
</script>

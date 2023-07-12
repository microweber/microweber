<script>
export default {
    methods: {
        showStyleEditor: function () {
            var dlg = mw.top().dialogIframe({
                url: mw.external_tool('rte_css_editor2'),
                title: mw.lang('Edit styles'),
                footer: false,
                width: 400,
                height: 'auto',
                autoHeight: true,
                overlay: false
            });
            dlg.iframe.addEventListener('load', () => {
                var selected = mw.app.liveEdit.elementHandle.getTarget();
                dlg.iframe.contentWindow.selectNode(selected)
            })
            this.cssEditorDialog = dlg;
            this.cssEditorIframe = dlg.iframe;
            var  styleEditorDialoginstance = this;

            $(this.cssEditorDialog).on('Remove', function () {
                styleEditorDialoginstance.removeStyleEditor();
            })


            $(this.cssEditorDialog).on('Hide', function () {
                styleEditorDialoginstance.removeStyleEditor();
            })



        },
        removeStyleEditor: function () {
            if (this.cssEditorDialog) {
               // this.cssEditorDialog.remove();
                this.cssEditorDialog = null;
                this.cssEditorIframe = null;
                this.isOpened = false;
            }
        }

    },

    data() {
        return {
            isOpened: false,
            cssEditorIframe: null,
            cssEditorDialog: null
        }
    },
    mounted() {

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'style-editor') {
                if(!this.isOpened) {
                    this.showStyleEditor();
                    this.isOpened = true;
                }
            } else {
                this.removeStyleEditor();
                this.isOpened = false;
            }
        });

        var instance = this;
        mw.app.canvas.on('canvasDocumentClick', function () {
            if (instance.isOpened) {
                var selected = mw.app.liveEdit.elementHandle.getTarget();
                if (selected && instance.cssEditorIframe && instance.cssEditorIframe.contentWindow && instance.cssEditorIframe.contentWindow.selectNode) {
                    instance.cssEditorIframe.contentWindow.selectNode(selected)
                }
            }
        });

        mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {
            if (instance) {
                // remove editor if the frame is changed
                instance.removeStyleEditor();
            }
        });
        mw.app.canvas.on('liveEditCanvasBeforeUnload', function (frame) {
            if (instance) {
                // remove editor if the frame is changed
                instance.removeStyleEditor();
            }
        });
    }

}
</script>

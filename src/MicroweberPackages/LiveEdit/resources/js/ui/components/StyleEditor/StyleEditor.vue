<script>
export default {
    methods: {
        showStyleEditor: function () {
            var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_rte_css_editor2_editor';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);


            var dlg = mw.top().dialogIframe({
                url: src,
                title: mw.lang('Edit styles'),
                footer: false,
                width: 400,
                height: 'auto',
                autoHeight: true,
                overlay: false
            });
            dlg.iframe.addEventListener('load', () => {
              //  var selected = mw.app.liveEdit.elementHandle.getTarget();
              //  dlg.iframe.contentWindow.selectNode(selected)



                        var event = new CustomEvent('refreshSelectedElement')
                dlg.iframe.contentWindow.document.dispatchEvent(event);



            })
            this.cssEditorDialog = dlg;
            this.cssEditorIframe = dlg.iframe;
            var styleEditorDialoginstance = this;

            $(this.cssEditorDialog).on('Remove', function () {
                styleEditorDialoginstance.markAsRemoved();
            })


            $(this.cssEditorDialog).on('Hide', function () {
                styleEditorDialoginstance.markAsRemoved();
            })


        },
        removeStyleEditor: function () {
            if (this.cssEditorDialog) {

                this.cssEditorDialog.remove();
                $('#mw_global_rte_css_editor2_editor').remove();
                this.markAsRemoved();
            }
        },
        markAsRemoved: function () {
            this.cssEditorDialog = null;
            this.cssEditorIframe = null;
            this.isOpened = false;
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
                if (!this.isOpened) {
                    this.showStyleEditor();
                    this.isOpened = true;
                }
            } else {
                this.removeStyleEditor();
                this.isOpened = false;
            }
        });

        var instance = this;


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

        mw.top().app.canvas.on('canvasDocumentClick', function () {
            if (instance.isOpened) {
                if (instance.cssEditorIframe) {
                    var activeNode = mw.top().app.liveEdit.getSelectedNode();

                    var can = mw.top().app.liveEdit.canBeElement(activeNode)
                    if(can){
                        //check if has Id
                        var targetWindow = mw.top().app.canvas.getWindow();

                        var id = activeNode.id;
                        if(!id){
                            targetWindow.mw.tools.generateSelectorForNode(activeNode);
                          //  activeNode.id = id;
                        }
                    }


                    var event = new CustomEvent('refreshSelectedElement')
                   instance.cssEditorIframe.contentWindow.document.dispatchEvent(event);
                }
            }

        });
    }

}
</script>

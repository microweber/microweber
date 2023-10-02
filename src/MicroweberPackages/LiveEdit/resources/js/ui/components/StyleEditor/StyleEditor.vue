<script>
export default {
    methods: {
        showStyleEditor: function () {
            //var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2';
            var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2/rte_editor_vue';
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
                width: 350,
                height: 'auto',
                autoHeight: true,
                overlay: false
            });
            var x = mw.top().app.canvas.getWindow().innerWidth - 400;
            var y = mw.top().app.canvas.getWindow().innerHeight - 400;
            if(x < 0){
                x = 0;
            }
            if(y < 0){
                y = 0;
            }
            dlg.position(x, y);


            dlg.iframe.addEventListener('load', () => {
                //  var selected = mw.app.liveEdit.elementHandle.getTarget();
                //  dlg.iframe.contentWindow.selectNode(selected)
                var styleEditorSettings = {
                    fieldSettings: {}
                };
                styleEditorSettings.fieldSettings.components = ['elementSelector','typography', 'spacing', 'background', 'border'];

                mw.top().app.dispatch('cssEditorSettings', styleEditorSettings);



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

        var styleEditorInstance = this;


        mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {
            if (styleEditorInstance) {
                // remove editor if the frame is changed
                styleEditorInstance.removeStyleEditor();
            }
        });
        mw.app.canvas.on('liveEditCanvasBeforeUnload', function (frame) {
            if (styleEditorInstance) {
                // remove editor if the frame is changed
                styleEditorInstance.removeStyleEditor();
            }
        });

        mw.top().app.canvas.on('canvasDocumentClick', function () {
            if (styleEditorInstance.isOpened) {
                if (styleEditorInstance.cssEditorIframe) {
                    var activeNode = mw.top().app.liveEdit.getSelectedNode();

                    var can = mw.top().app.liveEdit.canBeElement(activeNode)
                    if (can) {
                        //check if has Id
                        var targetWindow = mw.top().app.canvas.getWindow();
                        if(activeNode){
                            var id = activeNode.id;
                            if (!id) {
                                targetWindow.mw.tools.generateSelectorForNode(activeNode);
                                //  activeNode.id = id;
                            }
                            var event = new CustomEvent('refreshSelectedElement')
                            styleEditorInstance.cssEditorIframe.contentWindow.document.dispatchEvent(event);
                        }
                    }
                }
            }
        });


      mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {


        mw.app.editor.on('insertLayoutRequest', function (element) {
          // close open html editor when layout is inserted
          styleEditorInstance.removeStyleEditor();
        });
        mw.app.editor.on('insertModuleRequest', function (element) {
          // close open html editor when module is inserted
          styleEditorInstance.removeStyleEditor();
        });
      });


    }

}
</script>

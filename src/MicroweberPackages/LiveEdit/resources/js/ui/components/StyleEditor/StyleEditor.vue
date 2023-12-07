<template>
    <div class="ps-3" v-if="isOpened">
        <div class="d-flex align-items-center justify-content-between mt-5 mb-3">
            <h3 class="fs-2 font-weight-bold">Element Style Editor</h3>
            <span v-on:click="closeStyleEditor" class="mdi mdi-close x-close-modal-link" style="top: 30px;"></span>
        </div>
            <iframe @load="load" ref="styleEditorIframe" :src="buildIframeUrlStyleEditor()" style="width:100%;height:100vh;"
                frameborder="0"
                allowfullscreen></iframe>
    </div>
</template>


<script>
export default {
    methods: {
        load: function(){
            let iframe = this.$refs.styleEditorIframe;


            var styleEditorSettings = {
                fieldSettings: {}
            };
            styleEditorSettings.fieldSettings.components = [
                'elementSelector',
                'typography',
                'spacing',
                'container',
                'grid',
                'background',
                'animations',
                'classes',
                'roundedCorners',
                'border'
            ];

            mw.top().app.dispatch('cssEditorSettings', styleEditorSettings);


            var event = new CustomEvent('refreshSelectedElement')
            iframe.contentWindow.document.dispatchEvent(event);

            this.cssEditorIframe = iframe;



        },
        closeStyleEditor: function () {
            this.removeStyleEditor();
            this.emitter.emit("live-edit-ui-show", 'template-settings')
           // this.emitter.emit("live-edit-ui-show", 'close-element-style-editor')
        },

        buildIframeUrlStyleEditor: function (url) {

            var moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2/rte_editor_vue';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_rte_css_editor2_editor';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            return src;

        },
        showStyleEditorModal: function () {


            var src =  this.buildIframeUrlStyleEditor();


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
            if (x < 0) {
                x = 0;
            }
            if (y < 0) {
                y = 0;
            }
            dlg.position(x, y);


            dlg.iframe.addEventListener('load', () => {
                //  var selected = mw.app.liveEdit.elementHandle.getTarget();
                //  dlg.iframe.contentWindow.selectNode(selected)
                var styleEditorSettings = {
                    fieldSettings: {}
                };
                styleEditorSettings.fieldSettings.components = ['elementSelector', 'typography', 'spacing', 'background', 'border'];

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

            }
            this.markAsRemoved();
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
            hasBeenOpenedBeforeUnload: false,
            cssEditorIframe: null,
            cssEditorDialog: null
        }
    },
    mounted() {

        this.emitter.on("live-edit-ui-show", show => {

            if (show == 'style-editor') {
                if (!this.isOpened) {
                //    this.showStyleEditorModal();
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
              styleEditorInstance.isOpened = false;
                if(styleEditorInstance.hasBeenOpenedBeforeUnload){
                    styleEditorInstance.isOpened = true;
                }
              styleEditorInstance.isOpened = true;
             }
        });
        // mw.app.canvas.on('liveEditCanvasBeforeUnload', function (frame) {
        //     if (styleEditorInstance) {
        //         if(styleEditorInstance.isOpened) {
        //             styleEditorInstance.hasBeenOpenedBeforeUnload = true;
        //         }
        //         // remove editor if the frame is changed
        //         styleEditorInstance.removeStyleEditor();
        //     }
        // });

  /*
        mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {
            mw.top().app.liveEdit.handles.get('element').on('targetChange', target => {
                if (styleEditorInstance.isOpened) {
                    if (styleEditorInstance.cssEditorIframe) {
                mw.top().app.dispatch('mw.elementStyleEditor.selectNode', target);
                    }
                }
            });

            mw.top().app.liveEdit.handles.get('module').on('targetChange', target => {
                if (styleEditorInstance.isOpened) {
                    if (styleEditorInstance.cssEditorIframe) {
                        mw.top().app.dispatch('mw.elementStyleEditor.selectNode', target);

                    }
                }
            });
        });
*/

        // mw.top().app.canvas.on('canvasDocumentClick', function (event) {
        //     if (styleEditorInstance.isOpened) {
        //         if (styleEditorInstance.cssEditorIframe) {
        //             var activeNode = mw.top().app.liveEdit.getSelectedNode();
        //             if(activeNode) {
        //                 mw.top().app.dispatch('mw.elementStyleEditor.selectNode', activeNode);
        //             }
        //         }}
        //    });


        mw.top().app.canvas.on('canvasDocumentClick', function (event) {
            if (styleEditorInstance.isOpened) {
                if (styleEditorInstance.cssEditorIframe) {
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
                }
            }
        });


        mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {


            mw.app.editor.on('insertLayoutRequest', function (element) {
                // close open html editor when layout is inserted
             //   styleEditorInstance.removeStyleEditor();
            });
            mw.app.editor.on('insertModuleRequest', function (element) {
                // close open html editor when module is inserted
            //    styleEditorInstance.removeStyleEditor();
            });
        });


    }

}
</script>
<script setup>
</script>

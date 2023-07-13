<script>
export default {
    methods: {
        showHtmlEditor: function () {


            var moduleType = 'editor/code_editor';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_html_editor';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);


            var dlg = mw.top().dialogIframe({
               // url: mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true',
                url: src,
                title: mw.lang('Edit Code'),
                footer: false,
                width: 400,
                height: 600,
               // height: 'auto',
                autoHeight: true,
                overlay: false
            });
            dlg.iframe.addEventListener('load', () => {
                var selected = mw.app.liveEdit.elementHandle.getTarget();
                //  dlg.iframe.contentWindow.selectNode(selected)
            })
            this.htmlEditorDialog = dlg;
            this.htmlEditorIframe = dlg.iframe;
            var htmlEditorDialoginstance = this;
            $(this.htmlEditorDialog).on('Remove', function () {
                htmlEditorDialoginstance.markAsRemoved();
            })


            $(this.cssEditorDialog).on('Hide', function () {
                htmlEditorDialoginstance.markAsRemoved();
            })

        },
        removeHtmlEditor: function () {

            if (this.htmlEditorDialog) {
                this.htmlEditorDialog.remove();
                this.markAsRemoved();

            }
        },
        markAsRemoved: function () {

            this.htmlEditorDialog = null;
            this.htmlEditorIframe = null;
            this.isOpened = false;

        }

    },

    data() {
        return {
            isOpened: false,
            htmlEditorIframe: null,
            htmlEditorDialog: null
        }
    },
    mounted() {
        this.emitter.on("live-edit-ui-hide", hide => {
            if (hide == 'html-editor') {
                this.removeHtmlEditor();
                this.isOpened = false;
            }
        });
        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'html-editor') {
                if (!this.isOpened) {
                    this.showHtmlEditor();
                    this.isOpened = true;
                }
            } else {
                // this.removeHtmlEditor();
                //  this.isOpened = false;
            }
        });

        var htmlEditorInstance = this;
        // mw.app.canvas.on('canvasDocumentClick', function () {
        //     if (instance.isOpened) {
        //         var selected = mw.app.liveEdit.elementHandle.getTarget();
        //         if (selected && instance.htmlEditorIframe && instance.htmlEditorIframe.contentWindow && instance.htmlEditorIframe.contentWindow.selectNode) {
        //             instance.htmlEditorIframe.contentWindow.selectNode(selected)
        //         }
        //     }
        // });

        mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {
            if (htmlEditorInstance) {
                // remove editor if the frame is changed
                htmlEditorInstance.removeHtmlEditor();
            }
        });
        mw.app.canvas.on('liveEditCanvasBeforeUnload', function (frame) {


            if (htmlEditorInstance) {
                // remove editor if the frame is changed
                htmlEditorInstance.removeHtmlEditor();
            }
        });
    }

}
</script>

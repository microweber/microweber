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


            const leFrame = mw.top().app.canvas.getFrame();
            const leFrameParent = leFrame.parentElement;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);


            var id = mw.id('iframe-editor');


            var dlg = new mw.controlBox({
                content: '<div id="' + id + '"></div>',
                content: '',
                position:  'bottom',
                id: 'live_edit_side_holder',
                closeButton: true
            });


            dlg.closeButton.onclick = function(){
                dlg.remove();
                leFrame.style.transition = 'none';
                leFrameParent.style.transition = 'none';
                document.documentElement.style.setProperty('--iframe-height-minus',  0 + 'px');
                leFrame.style.transition = '';
                leFrameParent.style.transition = '';
            };



            var htmlEditorDialoginstance = this;
            $(this.htmlEditorDialog).on('Remove', function () {
                htmlEditorDialoginstance.markAsRemoved();
            });


            $(this.cssEditorDialog).on('Hide', function () {
                htmlEditorDialoginstance.markAsRemoved();
            });

            var frame = document.createElement('iframe');
            frame.src = src;
            frame.style.top = '0';
            frame.style.left = '0';
            frame.style.width = '100%';
            frame.style.height = '100%';
            frame.style.position = 'absolute';
            frame.dataset.autoHeight = 'false';



            this.htmlEditorDialog = dlg;
            this.htmlEditorIframe = frame;

            mw.top().win.document.documentElement.style.setProperty('--iframe-height-minus',  300 + 'px');

            mw.spinner({element: dlg.boxContent, decorate: true})



            frame.addEventListener('load', () => {
                var selected = mw.app.liveEdit.elementHandle.getTarget();
                mw.spinner({element: dlg.boxContent, decorate: true}).remove()

                var css = `
                     html,body{
                        overflow: hidden;
                     }

                `;

                try{
                    if(frame.contentWindow && frame.contentWindow.document) {
                        var style = frame.contentWindow.document.createElement('style');
                        style.textContent = css;
                        frame.contentWindow.document.body.appendChild(style);

                    }
                } catch (err) {}

            });







            $(dlg.boxContent)
            .css('minHeight', 100)
            .css('height', 300)
            .append(frame)
            .resizable({
                handles: "n",
                start: function( event, ui ) {
                    frame.style.pointerEvents = 'none';
                    leFrame.style.pointerEvents = 'none';
                    leFrame.style.transition = 'none';
                    leFrameParent.style.transition = 'none';
                },
                stop: function( event, ui ) {
                    frame.style.pointerEvents = 'all';
                    leFrame.style.pointerEvents = 'all';
                    leFrame.style.transition = '';
                    leFrameParent.style.transition = '';
                }
            })
            .on('resize', function(event, ui){
                const height = ui.size.height;
                document.documentElement.style.setProperty('--iframe-height-minus',  height + 'px');
            });




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
                    // this.isOpened = true;
                }
            }
        });
        this.emitter.on("show-code-editor", show => {


                    this.showHtmlEditor();

        });

        var htmlEditorInstance = this;


        mw.app.canvas.on('liveEditCanvasLoaded', function (frame) {
            if (htmlEditorInstance) {
                // remove editor if the frame is changed
                htmlEditorInstance.removeHtmlEditor();
            }

          mw.app.editor.on('insertLayoutRequest', function (element) {
            // close open html editor when layout is inserted
            htmlEditorInstance.removeHtmlEditor();
          });
          mw.app.editor.on('insertModuleRequest', function (element) {
            // close open html editor when module is inserted
            htmlEditorInstance.removeHtmlEditor();
          });
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

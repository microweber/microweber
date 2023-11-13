<script>
import liveEditHelpers from "../../../api-core/core/live-edit-helpers.service.js";
import {EditorComponent} from "../../../api-core/services/components/editor/editor";
import {liveEditComponent} from "../../../api-core/services/components/live-edit/live-edit";
import {DomService} from "../../../api-core/core/classes/dom";


export default {
    data() {

    },
    mounted() {

        mw.app.canvas.on('liveEditCanvasLoaded', () => {

            new EditorComponent();

        });

        mw.app.on('ready', () => {
            liveEditComponent();
            // const editImageDialog = async (url) => {
            //     return new Promise(resolve => {
            //       var editor = document.createElement('div');
            //         editor.style.height = 'calc(100vh - 300px)';
            //         editor.style.minHeight = '410px';
            //         const footer = mw.element(`
            //             <div class="d-flex justify-content-between w-100">
            //                 <button class="btn" data-action="cancel">Cancel</button>
            //                 <button class="btn btn-primary" data-action="save">Update</button>
            //             </div>
            //         `);
            //
            //
            //         let imageEditor;
            //
            //         const dlg = mw.dialog({
            //           width: 1000 ,
            //           title:  mw.lang("Edit image"),
            //           content: editor,
            //           footer: footer.get(0),
            //           id: 'mw-edit-image--dialog',
            //         });
            //
            //         footer.find('[data-action="cancel"]').on('click', function(){
            //             dlg.remove();
            //             resolve();
            //         });
            //
            //         footer.find('[data-action="save"]').on('click', function(){
            //
            //
            //             var _img = new Image();
            //             _img.src = imageEditor.getCurrentImgData().imageData.imageBase64
            //             mw.top().app.normalizeBase64Image(_img, function(){
            //               console.log(this);
            //               resolve(this.src);
            //              })
            //
            //             dlg.remove();
            //
            //         })
            //         $(dlg).on('remove', () => {
            //           resolve()
            //           mw.app.liveEdit.play();
            //         })
            //
            //         imageEditor = editImage(url, editor, dlg)
            //     });
            // }

            // mw.app.editImageDialog = editImageDialog;

            mw.app.editor.on('elementSettingsRequest', (element) => {
                if (element.nodeName === 'IMG') {

                    mw.app.editImageDialog.editImage(element.src, (imgData) => {
                        if (typeof imgData !==  'undefined' && imgData.src) {

                            element.src = imgData.src

                            if (imgData.sizeChanged) {
                                // reset width and height
                                //   element.style.width = '';
                                element.style.height = 'auto';
                            }
                        }
                        mw.top().app.registerChange(element);
                        mw.app.liveEdit.play();
                    });



                } else if (element.style.backgroundImage) {
                    var bg = element.style.backgroundImage.trim().split('url(')[1];

                    if (bg) {
                        bg = bg.split(')')[0]
                            .trim()
                            .split('"')
                            .join('');
                        //var imData = await mw.app.editImageDialog.editImageUrl(bg);

                        mw.app.editImageDialog.editImage(bg, (imgData) => {
                            if (typeof imgData !==  'undefined' && imgData.src) {

                                element.style.backgroundImage = `url("${imgData.src}")`;
                            }
                            mw.top().app.registerChange(element);
                            mw.app.liveEdit.play();
                        });



                    }

                } else {
                    // open style editor
                    this.emitter.emit('live-edit-ui-show', 'style-editor');

                }
            })

            mw.app.editor.on('editNodeStyleRequest', async (element) => {
                this.emitter.emit('live-edit-ui-show', 'style-editor');
            });



            mw.app.editor.on('editNodeRequest', async (element) => {


                mw.app.registerChangedState(element);


                function imagePicker(onResult) {
                    var dialog;
                    var picker = new mw.filePicker({
                        type: 'images',
                        label: false,
                        autoSelect: false,
                        footer: true,
                        _frameMaxHeight: true,
                        onResult: onResult
                    });
                    dialog = mw.top().dialog({
                        content: picker.root,
                        title: mw.lang('Select image'),
                        footer: false,
                        width: 860,


                    });
                    picker.$cancel.on('click', function () {
                        dialog.remove()
                    })


                    $(dialog).on('Remove', () => {

                        mw.app.liveEdit.play();
                        const target = mw.top().app.liveEdit.handles.get('element').getTarget();
                        mw.top().app.liveEdit.handles.get('element').set(null);
                        mw.top().app.liveEdit.handles.get('element').set(target);
                    })
                    return dialog;
                }

                if (liveEditHelpers.targetIsIcon(element)) {
                    const iconPicker = mw.app.get('iconPicker').pickIcon(element);


                    iconPicker.picker.on('sizeChange', val => {
                        element.style.fontSize = `${val}px`;
                        mw.top().app.liveEdit.handles.get('element').position(mw.top().app.liveEdit.handles.get('element').getTarget())
                        mw.top().app.registerChange(element);
                    });

                    iconPicker.picker.on('colorChange', val => {

                        element.style.color = `${val}`;
                        mw.top().app.registerChange(element);
                    });

                    iconPicker.picker.on('reset', val => {

                        element.style.color = ``;
                        element.style.fontSize = ``;
                        mw.top().app.registerChange(element);

                    });


                    const icon = await iconPicker.promise();

                    setTimeout(function(){
                        mw.app.liveEdit.play();
                        var target = mw.top().app.liveEdit.handles.get('element').getTarget();
                        if(!target){
                            return;
                        }
                        mw.top().app.liveEdit.handles.get('element').set(null);
                        mw.top().app.liveEdit.handles.get('element').set(target);
                    }, 70)


                } else if (element.classList.contains('mw-img-placeholder')) {

                    var dialog = imagePicker(function (res) {

                        mw.top().app.registerUndoState(element);
                        var url = res.src ? res.src : res;
                        if (!url) return;
                        url = url.toString();


                        var id = mw.id('element');
                        var parent = element.parentNode;

                        $(element).replaceWith(`<img class="element" id="${id}" src="${url}" alt="${res.name || ''}">`);


                        mw.app.liveEdit.play();
                        dialog.remove();

                        mw.top().app.registerChangedState(parent);
                    })


                } else if (element.nodeName === 'IMG') {

                    var dialog = imagePicker(function (res) {

                        mw.top().app.registerUndoState(element);
                        var url = res.src ? res.src : res;
                        if (!url) return;
                        url = url.toString();
                        element.src = url;
                       element.style.objectFit = '';
                        element.style.width = 'auto';
                        element.style.height = 'auto';

                        mw.app.liveEdit.play();
                        dialog.remove();

                        mw.top().app.registerChangedState(element);
                    })


                }  else if (element.style && element.style.backgroundImage) {
                    var bg = element.style.backgroundImage.trim().split('url(')[1];

                    if (bg) {
                        mw.top().app.registerUndoState(element);
                        bg = bg.split(')')[0]
                            .trim()
                            .split('"')
                            .join('');
                        var dialog = imagePicker(function (res) {
                            mw.top().app.registerChange(element);
                            var url = res.src ? res.src : res;
                            if (!url) return;
                            url = url.toString();
                            element.style.backgroundImage = `url(${url})`

                            mw.app.liveEdit.play();
                            dialog.remove();

                            mw.top().app.registerChangedState(element);
                        })

                    }

                } else {



                    var isInaccessibleEdit = mw.top().app.liveEdit.liveEditHelpers.targetIsDisabledWriteInEditField(element);
                    if (isInaccessibleEdit) {
                        return;
                    }




                    if (element.isContentEditable) {
                        return
                    }



                    if (DomService.parentsOrCurrentOrderMatchOrOnlyFirst(element, ['noedit', 'edit'])) {
                        return;
                    }

                    if (!DomService.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(element, ['allow-edit', 'no-edit'])) {
                        return;
                    }







                    setTimeout(() => {


                        let editTarget = element;

                        var isSafeMode = DomService.parentsOrCurrentOrderMatchOrOnlyFirst(editTarget, ['safe-mode', 'regular-mode']);

                        if(!isSafeMode) {
                            editTarget = DomService.firstParentOrCurrentWithClass(editTarget, 'edit')
                        }


                        if(!editTarget) {
                            return;
                        }




                        editTarget.contentEditable = true;

                        element.focus();

                        editTarget.contentEditable = true;
                        mw.app.liveEdit.pause();

                   //     mw.app.richTextEditor.smallEditorInteract(element);
                   //     mw.app.richTextEditor.positionSmallEditor(element);


                        mw.app.wyswygEditor.showEditorOnElement(element);


                        // element.querySelectorAll('[contenteditable], .allow-drop[contenteditable]').forEach(node => {
                        //     node.contentEditable = 'inherit';
                        // })
                        // editTarget.querySelectorAll('[contenteditable], .allow-drop[contenteditable]').forEach(node => {
                        //     node.contentEditable = 'inherit';
                        // })
                    }, 300);

                }

                mw.app.liveEdit.handles.hide();
                mw.app.liveEdit.pause();
            });


            mw.app.canvas.on('canvasDocumentClick', (event) => {
                if (mw.app.isPreview()) {
                    return;
                }
                var can = mw.app.liveEdit.canBeEditable(event.target)
                if (!can) {
                    return;
                }

                var tagName = event.target.nodeName;
                //image click with link as a parent node and prevent default
                if (tagName == 'IMG' && event.target.parentNode.nodeName == 'A') {
                    //  event.stopPropagation()
                    event.preventDefault()
                }

            })




          mw.app.canvas.on('reloadCustomCss', (event) => {

              mw.tools.eachWindow(function (win) {

                var customFontsStylesheet = win.document.getElementById("mw-custom-user-css");

                if (customFontsStylesheet != null) {
                  var customFontsStylesheetRestyle = mw.settings.api_url + 'template/print_custom_css?time=' + Math.random(0, 10000);
                  customFontsStylesheet.href = customFontsStylesheetRestyle;
                }

                var customFontsStylesheetFonts = win.document.getElementById("mw-custom-user-fonts");
                if (customFontsStylesheetFonts != null) {
                  var customFontsStylesheetFontsRestyle = mw.settings.api_url + 'template/print_custom_css_fonts?time=' + Math.random(0, 10000);
                  customFontsStylesheetFonts.href = customFontsStylesheetFontsRestyle;
                }
              });
          })


        });

    }
}
</script>

<template>
    <div>
        <div class="toolbar-nav" id="mw-live-edit-editor"></div>
    </div>
</template>


<style scoped>


</style>

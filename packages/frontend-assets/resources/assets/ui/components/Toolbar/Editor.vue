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

                    mw.app.liveEdit.elementHandleContent.elementActions.editImageWithEditor(element);
/*

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
*/



                } else if (element.style.backgroundImage) {

                    mw.app.liveEdit.elementHandleContent.elementActions.editBackgroundImage(element);


                    //
                    // var bg = element.style.backgroundImage.trim().split('url(')[1];
                    //
                    // if (bg) {
                    //     bg = bg.split(')')[0]
                    //         .trim()
                    //         .split('"')
                    //         .join('');
                    //     //var imData = await mw.app.editImageDialog.editImageUrl(bg);
                    //
                    //     mw.app.editImageDialog.editImage(bg, (imgData) => {
                    //         if (typeof imgData !==  'undefined' && imgData.src) {
                    //
                    //             element.style.backgroundImage = `url("${imgData.src}")`;
                    //         }
                    //         mw.top().app.registerChange(element);
                    //         mw.app.liveEdit.play();
                    //     });
                    //
                    //
                    //
                    // }

                } else {
                    // open style editor
                    this.emitter.emit('live-edit-ui-show', 'style-editor');

                }
            })

            mw.app.editor.on('editNodeStyleRequest', async (element) => {
                this.emitter.emit('live-edit-ui-show', 'style-editor');
            });




          // mw.app.canvas.on('canvasDocumentKeydown', async (event) => {
          //
          //     if (mw.app.isPreview()) {
          //         return;
          //
          //
          // })
          mw.app.canvas.on('canvasDocumentInput', async (event) => {

              if (mw.app.isPreview()) {
                  return;
              }
              var can = mw.app.liveEdit.canBeEditable(event.target)
              if (!can) {
                  return;
              }

            mw.app.registerChangedState(event.target);

          })
          mw.app.canvas.on('canvasDocumentClick', async (event) => {
            var element = event.target;

            //check if element is empty and set cursor to 0
            if (element && element.getAttribute('data-mwplaceholder') && element.innerHTML.trim() === '' ) {
                // check if class .element
                var elementClass = DomService.hasAnyOfClasses(element, ['element']);
                if (elementClass) {
                  mw.app.wyswygEditor.setCursorPos(0, element)
              }
            }
          });




          let _currentRichtextTarget = null;
          let _currentRichtextTargetditor = null;

          const getRichtextditor = () => {
            if(_currentRichtextTargetditor) {
                if(Array.isArray(_currentRichtextTargetditor)) {
                    return _currentRichtextTargetditor[0]
                }
            }
            return _currentRichtextTargetditor;
          }

            mw.top().app.canvas.on('canvasDocumentClickStart', (e) => {






                if(_currentRichtextTarget) {
                    if(!_currentRichtextTarget.contains(e.target) && !mw.tools.firstParentOrCurrentWithClass(e.target, 'tox')) {
                        const editor = getRichtextditor();
                        if(editor) {
                            getRichtextditor().destroy();
                        }

                        _currentRichtextTarget = null;
                        _currentRichtextTargetditor = null;
                    }
                }


            });


            mw.app.editor.on('editNodeRequest', async (element) => {

                if (mw.app.isPreview()) {
                    console.log('Cannot edit in preview mode');
                    return;
                }


                function imagePicker(onResult) {
                    return mw.app.liveEdit.elementHandleContent.elementActions.imagePicker(onResult);
                }




                async function richtext (isRichtext) {
                    if (isRichtext.classList.contains('mce-content-body')) {
                        isRichtext.contentEditable = true;
                    }
                    if (!isRichtext.classList.contains('mce-content-body')) {



                        if(isRichtext.firstChild && isRichtext.firstChild.nodeType === 3) {
                            isRichtext.firstChild.textContent = isRichtext.firstChild.textContent.replace(/(\r\n|\n|\r)/gm, '').trim();
                        }
                        if(isRichtext.lastChild && isRichtext.lastChild.nodeType === 3) {
                            isRichtext.lastChild.textContent = isRichtext.lastChild.textContent.replace(/(\r\n|\n|\r)/gm, '').trim()
                        }

                        isRichtext.contentEditable = true;

                        _currentRichtextTarget = isRichtext;


                        isRichtext.querySelectorAll('.module').forEach(node => {
                            node.contentEditable = false;
                        })



                        mw.app.richTextEditor.smallEditor.hide()





                    _currentRichtextTargetditor = await mw.top().app.canvas.getWindow().tinymce.init({
                        target: isRichtext,

                        forced_root_block : 'mw-element',
                        newline_behavior: 'linebreak',
                        inline: true,
                        promotion: false,
                        statusbar: false,
                        menubar:false,
                        //menubar: 'edit insert view format table tools',
                        noneditable_class: 'module',
                        toolbar_sticky: true,
                        remove_linebreaks : false,
                        /*force_br_newlines : false,
                        force_p_newlines : false,

                        newline_behavior: 'linebreak',
                        newline_behavior: '',*/
                        plugins: [

                            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                            'insertdatetime', 'media', 'table', 'help',
                          //  'image', 'editimage'
                            'image'
                        ],

                        toolbar: ' blocks | ' +
                        'bold italic forecolor backcolor | mwLink unlink | alignleft aligncenter  ' +
                        'alignright alignjustify | fontfamily fontsizeinput | bullist numlist outdent indent | ' +
                        'table quicktable ' +
                        'removeformat ',
                        // table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',

                        init_instance_callback: (editor) => {

                            isRichtext.querySelectorAll('p:empty').forEach(node => node.remove())

                            isRichtext.querySelectorAll('.module').forEach(node => {
                                node.contentEditable = false;
                            })

                            editor.on('Change  ', (e) => {

                                isRichtext.__mceEditor = _currentRichtextTargetditor;

                                mw.app.registerChangedState(isRichtext, true);


                            });

                            editor.focus()
                        },
                        setup: (editor) => {

                        editor.ui.registry.addButton('mwLink', {
                        icon: '<svg viewBox="0 0 24 24"> <path fill="currentColor" d="M3.9,12C3.9,10.29 5.29,8.9 7,8.9H11V7H7A5,5 0 0,0 2,12A5,5 0 0,0 7,17H11V15.1H7C5.29,15.1 3.9,13.71 3.9,12M8,13H16V11H8V13M17,7H13V8.9H17C18.71,8.9 20.1,10.29 20.1,12C20.1,13.71 18.71,15.1 17,15.1H13V17H17A5,5 0 0,0 22,12A5,5 0 0,0 17,7Z" /></svg>',
                        icon: 'link',
                        onAction: (_) =>  {


                        var linkEditor = new mw.LinkEditor({
                            mode: 'dialog',
                            hideTextFied: true
                        });



                        linkEditor.promise().then(function (data){
                            var modal = linkEditor.dialog;
                            if(data) {

                                editor.execCommand('CreateLink', false, data.url);
                                modal.remove();
                            } else {
                                modal.remove();
                            }
                        });
                        }
                        });


                        },

                    });




                }
                }

                const isRichtext = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['mw-richtext']);





                if (isRichtext) {

                    await richtext(isRichtext);


            } else if (liveEditHelpers.targetIsIcon(element)) {
                    const iconPicker = mw.app.get('iconPicker').pickIcon(element);

                     iconPicker.picker.on('iconReplaced', rdata => {


                        if(rdata.type === 'image') {
                            var img = mw.element(`<img src="${rdata.url}" class="element">`);

                            var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode','edit', 'safe-mode']);
                            if(edit) {
                                mw.top().app.registerChangedState(edit, true);
                            }
                            mw.element(element).after(img);

                            mw.element(element).remove();

                            if(edit) {
                                mw.top().app.registerChangedState(edit, true);
                            }

                            iconPicker.picker.dialog().remove()
                        }

                     })
                     iconPicker.picker.on('sizeChange', val => {
                         var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode','edit', 'safe-mode']);
                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }

                        element.style.fontSize = `${val}px`;
                        mw.top().app.liveEdit.handles.get('element').position(mw.top().app.liveEdit.handles.get('element').getTarget())

                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }


                    });
                    iconPicker.picker.on('select', val => {
                        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode','edit', 'safe-mode']);
                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }

                    });
                    iconPicker.picker.on('colorChange', val => {
                        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode','edit', 'safe-mode']);
                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }

                        element.style.color = `${val}`;

                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }

                    });

                    iconPicker.picker.on('reset', val => {
                        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode','edit', 'safe-mode']);

                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }

                        element.style.color = ``;
                        element.style.fontSize = ``;


                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }


                    });


                    const icon = await iconPicker.promise();

                    setTimeout(function(){
                        mw.app.liveEdit.play();
                        var edit = mw.tools.firstParentOrCurrentWithAnyOfClasses(element, ['regular-mode','edit', 'safe-mode']);
                        if(edit) {
                            mw.top().app.registerChangedState(edit);
                        }
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

                    mw.app.liveEdit.elementHandleContent.elementActions.editImage(element);


                }  else if (element.style && element.style.backgroundImage) {
                    var bg = element.style.backgroundImage.trim().split('url(')[1];

                    if (bg) {
                        mw.app.liveEdit.elementHandleContent.elementActions.editBackgroundImage(element)
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




                        let editTarget = element;

                        var isSafeMode = DomService.parentsOrCurrentOrderMatchOrOnlyFirst(editTarget, ['safe-mode', 'regular-mode']);

                        if(!isSafeMode) {
                            editTarget = DomService.firstParentOrCurrent(editTarget, '.edit,.regular-mode,[data-mw-free-element="true"]')
                        }


                        if(!editTarget) {
                            return;
                        }

                        if(editTarget.classList && editTarget.classList.contains('no-typing')) {
                            return;
                        }


                        if(!editTarget) {
                            return;
                        }

                        if(!editTarget.id && !editTarget.classList.contains('edit')) {
                            var canBeElement = mw.app.liveEdit.canBeElement(editTarget);
                            if(canBeElement) {

                                // @todo move this to the elementStyleEditor
                                //  makes new id if not exists on element
                                // maybe move this to the elementStyleEditor
                                // no need to do this here id we dont use the id
                                editTarget.id = mw.id('mw-element-');
                            }

                        }







                        editTarget.contentEditable = true;

                        element.focus();

                        editTarget.contentEditable = true;


                       // await richtext(editTarget)





                        mw.app.liveEdit.pause();


                        if(editTarget.__mw_movable) {

                            editTarget.__mw_movable.selfElement.style.display = 'none';
                        }




                       mw.app.richTextEditor?.smallEditorInteract(element);
                       mw.app.richTextEditor?.positionSmallEditor(element);

                       mw.app.richTextEditor?.observe();




                        element.querySelectorAll('[contenteditable], .allow-drop[contenteditable]').forEach(node => {
                            node.contentEditable = 'inherit';
                        })
                        editTarget.querySelectorAll('[contenteditable], .allow-drop[contenteditable]').forEach(node => {
                            node.contentEditable = 'inherit';
                        })






                        setTimeout(() => {

                            if(mw.top().app.canvas.getDocument().activeElement !== editTarget) {
                                editTarget.focus();
                            }
                        }, 110)


                   //  mw.app.wyswygEditor.initEditor(element);



                }

                mw.app.liveEdit.handles.hide();
                mw.app.liveEdit.pause();
            });

            mw.app.on('liveEditRefreshHandlesPosition', moduleId => {
                mw.app.liveEdit.handles.reposition();
            });

            mw.top().app.freeDraggableElementManager.initLayouts();
            mw.app.canvas.on('liveEditCanvasLoaded',function(frame){
                 mw.top().app.freeDraggableElementManager.initLayouts();
            });



            mw.top().app.on('mw.elementStyleEditor.applyCssPropertyToNode', function (data) {

                //free draggable element
                if(data.prop === 'position' && data.node) {
                    if(
                        data.val === 'static'
                        || data.val === ''
                        || data.val === null
                        || data.val === 'initial'
                        || data.val === 'inherit'
                        || data.val === 'unset'
                        || data.val === 'revert'
                    ) {
//@todo
                      //  mw.top().app.liveEdit.elementHandleContent.elementActions.destroyFreeDraggableElement(data.node)
                    //    mw.app.dispatch('liveEditRefreshHandlesPosition');

                    }
                    else if(
                        data.val === 'absolute'
                        || data.val === 'relative'
                        || data.val === 'fixed'
                        || data.val === 'sticky'
                    ) {
                        //@todo
                      //  mw.top().app.liveEdit.elementHandleContent.elementActions.makeFreeDraggableElement(data.node)
                        //mw.app.dispatch('liveEditRefreshHandlesPosition');
                    }
                }
            });


            mw.app.on('onModuleReloaded', moduleId => {
                // restart livewire on module reload

                var canvasWindow = mw.top().app.canvas.getWindow();
                if (canvasWindow) {
                    if (canvasWindow.Livewire) {

                       // setTimeout(() => {
                       //
                       //     try {
                       //         window.Livewire.initialRenderIsFinished = false;
                       //         canvasWindow.Livewire.start();
                       //     } catch (e) {
                       //
                       //         console.error(e);
                       //     }
                       //
                       //
                       // }, 100)

                    }
                }
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

          const getCustomCSSNodes = () => {
            const res = [];
            const selector = '#mw-custom-user-css,#mw-custom-user-fonts,#mw-template-settings';
            mw.tools.eachWindow(win => Array.from(win.document.querySelectorAll(selector)).filter(node => !!node.href).forEach(node => res.push(node)))
            return res;
          }



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


            const customCSSNodes = getCustomCSSNodes();
            let count = 0;

            if (!customCSSNodes.length) {
                mw.top().app.canvas.dispatch('reloadCustomCssDone');
            }
            const doneSingle = e => {
                count++;
                if (count === customCSSNodes.length) {
                    mw.top().app.canvas.dispatch('reloadCustomCssDone');
                }
            }

            customCSSNodes.forEach(node => mw.tools.refresh(node, doneSingle, doneSingle));



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

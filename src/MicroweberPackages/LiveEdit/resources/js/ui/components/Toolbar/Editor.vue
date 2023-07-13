


<script>

import { func } from "prop-types";
import {EditorComponent} from "../../../api-core/services/components/editor/editor";
import {liveEditComponent} from "../../../api-core/services/components/live-edit/live-edit";
import FilerobotImageEditor from 'filerobot-image-editor';
import { DomService } from "../../../api-core/core/classes/dom";

const { TABS, TOOLS } = FilerobotImageEditor;

const editImage = (url, target, dialog) => {
    const config = {
  source: url,

  showCanvasOnly: false,

  annotationsCommon: {
    fill: '#ff0000',
  },
  Text: { text: 'Double click to edit text' },
  Rotate: { angle: 90, componentType: 'slider' },
  translations: {
    profile: 'Profile',
    coverPhoto: 'Cover photo',
    facebook: 'Facebook',
    socialMedia: 'Social Media',
    fbProfileSize: '180x180px',
    fbCoverPhotoSize: '820x312px',
  },
  Crop: {
    presetsItems: [
      {
        titleKey: 'classicTv',
        descriptionKey: '4:3',
        ratio: 4 / 3,
      },
      {
        titleKey: 'cinemascope',
        descriptionKey: '21:9',
        ratio: 21 / 9,
      },
    ],
    presetsFolders: [
      {
        titleKey: 'socialMedia',
        groups: [
          {
            titleKey: 'facebook',
            items: [
              {
                titleKey: 'profile',
                width: 180,
                height: 180,
                descriptionKey: 'fbProfileSize',
              },
              {
                titleKey: 'coverPhoto',
                width: 820,
                height: 312,
                descriptionKey: 'fbCoverPhotoSize',
              },
            ],
          },
        ],
      },
    ],
  },
  tabsIds: [TABS.FINETUNE, TABS.FILTERS, TABS.ADJUST, TABS.ANNOTATE, /*TABS.WATERMARK*/], // or ['Adjust', 'Annotate', 'Watermark']
  defaultTabId: TABS.FINETUNE, // or 'Annotate'
  defaultToolId: TOOLS.TEXT, // or 'Text'
};



    // Assuming we have a div with id="editor_container"
    const filerobotImageEditor = new FilerobotImageEditor(
    target,
    config,
    );

    filerobotImageEditor.render({

    });
    return filerobotImageEditor;
}


export default {
    data() {

    },
    mounted() {

        mw.app.canvas.on('liveEditCanvasLoaded', () => {

            new EditorComponent();

        });

        mw.app.on('ready', () => {
            liveEditComponent();
            const editImageDialog = async (url) => {
                return new Promise(resolve => {
                  var editor = document.createElement('div');
                    editor.style.height = 'calc(100vh - 300px)';
                    editor.style.minHeight = '410px';
                    const footer = mw.element(`
                        <div class="d-flex justify-content-between w-100">
                            <button class="btn" data-action="cancel">Cancel</button>
                            <button class="btn btn-primary" data-action="save">Update</button>
                        </div>
                    `);


                    let imageEditor;

                    const dlg = mw.dialog({
                      width: 1000 ,
                      title:  mw.lang("Edit image"),
                      content: editor,
                      footer: footer.get(0),
                      id: 'mw-edit-image--dialog',
                    });

                    footer.find('[data-action="cancel"]').on('click', function(){
                        dlg.remove();
                        resolve();
                    });

                    footer.find('[data-action="save"]').on('click', function(){
                        resolve(imageEditor.getCurrentImgData().imageData.imageBase64);
                        dlg.remove();

                    })
                    $(dlg).on('remove', () => {
                      resolve()
                      mw.app.get('liveEdit').play();
                    })

                    imageEditor = editImage(url, editor, dlg)
                });
            }

            mw.app.editor.on('elementSettingsRequest', async (element) => {
              if(element.nodeName === 'IMG') {
                  var src = await editImageDialog(element.src);

                  if(src) {
                    element.src = src
                  }
                  mw.app.get('liveEdit').play();

              } else if(element.style.backgroundImage) {
                  var bg =  element.style.backgroundImage.trim().split('url(')[1];

                  if(bg) {
                    bg = bg.split(')')[0]
                              .trim()
                              .split('"')
                              .join('');
                              var src = await editImageDialog(bg);

                    if(src) {

                      element.style.backgroundImage = `url(${src})`
                    }
                    mw.app.get('liveEdit').play();

                  }

                } else {

                  this.emitter.emit('live-edit-ui-show', 'style-editor');
                 // mw.app.editor.dispatch('elementSettingsRequest', el);
                  // const dlg = mw.top().dialogIframe({
                  //     url: mw.external_tool('rte_css_editor2'),
                  //     title: mw.lang('Edit styles'),
                  //     footer: false,
                  //     width: 400,
                  //     height: 'auto',
                  //     autoHeight: true,
                  //     overlay: false
                  // });
                  // dlg.iframe.addEventListener('load', () => {
                  //   dlg.iframe.contentWindow.selectNode(element)
                  // })
              }
            })
            mw.app.editor.on('editNodeRequest', async (element) => {

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
                  picker.$cancel.on('click', function(){
                    dialog.remove()
                  })


                  $(dialog).on('Remove', () => {

                    mw.app.get('liveEdit').play();
                  })
                  return dialog;
                }
                if(element.nodeName === 'IMG') {

                  var dialog = imagePicker(function (res) {
                          var url = res.src ? res.src : res;
                          if(!url) return;
                          url = url.toString();
                          element.src = url;
                          mw.app.get('liveEdit').play();
                          dialog.remove();
                      })



                } else if(element.style.backgroundImage) {
                  var bg =  element.style.backgroundImage.trim().split('url(')[1];

                  if(bg) {
                    bg = bg.split(')')[0]
                              .trim()
                              .split('"')
                              .join('');
                        var dialog = imagePicker(function (res) {
                          var url = res.src ? res.src : res;
                          if(!url) return;
                          url = url.toString();
                          element.style.backgroundImage = `url(${url})`
                          mw.app.get('liveEdit').play();
                          dialog.remove();
                      })

                  }

                } else {
                  var target = DomService.firstParentOrCurrentWithClass(element, 'edit');
                  if(!target.classList.contains('safe-mode')){
                    element = target;
                    mw.app.get('liveEdit').handles.get('element').set(element);
                  }
                  element.contentEditable = true;
                  element.focus();

                    setTimeout(() => {
                      mw.app.richTextEditor.smallEditorInteract(element);
                      mw.app.richTextEditor.positionSmallEditor(element)
                    });

                }

                mw.app.get('liveEdit').handles.hide();
                mw.app.get('liveEdit').pause();
            });
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

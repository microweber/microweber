


<script>

import { func } from "prop-types";
import {EditorComponent} from "../../../api-core/services/components/editor/editor";
import {liveEditComponent} from "../../../api-core/services/components/live-edit/live-edit";
import FilerobotImageEditor from 'filerobot-image-editor';

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
        mw.app.on('ready', () => {
            new EditorComponent();
            liveEditComponent();
            const editImageDialog = async (url) => {
                return new Promise(resolve => {
                  var editor = document.createElement('div');
                    editor.style.height = 'calc(100vh - 300px)';
                    const footer = mw.element(`
                        <div class="d-flex justify-content-between w-100">
                            <button class="btn" data-action="cancel">Cancel</button>
                            <button class="btn btn-primary" data-action="save">Update</button>
                        </div>
                    `);

  
                    let imageEditor;

                    const dlg = mw.dialog({width: 1000 , title:  mw.lang("Edit image"), content: editor, footer: footer.get(0) });

                    footer.find('[data-action="cancel"]').on('click', function(){
                        dlg.remove();
                        resolve();
                    });

                    footer.find('[data-action="save"]').on('click', function(){
                        resolve(imageEditor.getCurrentImgData().imageData.imageBase64);
                        dlg.remove();

                    }) 
                    $(dlg).on('Remove', () => {
                      resolve()
                    })

                    imageEditor = editImage(url, editor, dlg)
                });
            }

            mw.app.editor.on('editNodeRequest', async (element) => {
                if(element.nodeName === 'IMG') {
                  var src = await editImageDialog(element.src);
                  console.log(src)
                  if(src) {
                    element.src = src
                  }
                  
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
                  }
                              
                } else {
                    element.contentEditable = true;
                    mw.app.richTextEditor.smallEditorInteract(element)
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

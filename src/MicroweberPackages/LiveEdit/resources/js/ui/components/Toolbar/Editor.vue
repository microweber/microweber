


<script>

import {EditorComponent} from "../../../api-core/services/components/editor/editor";
import {liveEditComponent} from "../../../api-core/services/components/live-edit/live-edit";

// import FilerobotImageEditor from 'https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js'; // Load library from NPM
/*or load from CDN as following and use (window.FilerobotImageEditor):
 
*/
const { TABS, TOOLS } = FilerobotImageEditor;

const editImage = (img, target, dialog) => {
    const config = {
  source: img.src,
  onSave: (editedImageObject, designState) => {
 
    img.src = editedImageObject.imageBase64;
    if(dialog) {
        dialog.remove()
    }
    
  },
  onModify: function(imageFileInfo, b) {
    console.log(1111, imageFileInfo, b)
  },
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
        // icon: CropClassicTv, // optional, CropClassicTv is a React Function component. Possible (React Function component, string or HTML Element)
      },
      {
        titleKey: 'cinemascope',
        descriptionKey: '21:9',
        ratio: 21 / 9,
        // icon: CropCinemaScope, // optional, CropCinemaScope is a React Function component.  Possible (React Function component, string or HTML Element)
      },
    ],
    presetsFolders: [
      {
        titleKey: 'socialMedia', // will be translated into Social Media as backend contains this translation key
        // icon: Social, // optional, Social is a React Function component. Possible (React Function component, string or HTML Element)
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
  tabsIds: [TABS.ADJUST, TABS.ANNOTATE, /*TABS.WATERMARK*/, TABS.FINETUNE, TABS.FILTERS], // or ['Adjust', 'Annotate', 'Watermark']
  defaultTabId: TABS.ANNOTATE, // or 'Annotate'
  defaultToolId: TOOLS.TEXT, // or 'Text'
};

    console.log(TABS)

    // Assuming we have a div with id="editor_container"
    const filerobotImageEditor = new FilerobotImageEditor(
    target,
    config,
    );

    filerobotImageEditor.render({
        onClose: (closingReason) => {
            console.log('Closing reason', closingReason);
            filerobotImageEditor.terminate();
        },
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
            mw.app.editor.on('editNodeRequest',function(element){
                if(element.nodeName === 'IMG') {
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
                        dlg.remove()
                    })
                    footer.find('[data-action="save"]').on('click', function(){
                        element.src = imageEditor.getCurrentImgData().imageData.imageBase64;
                        dlg.remove()
                    }) 

                    imageEditor = editImage(element, editor, dlg)
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

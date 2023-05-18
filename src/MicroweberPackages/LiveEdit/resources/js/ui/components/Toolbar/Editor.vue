<script>

import {EditorComponent} from "../../../api-core/services/components/editor/editor";
import {liveEditComponent} from "../../../api-core/services/components/live-edit/live-edit";

export default {
    data() {

    },
    mounted() {
        mw.app.on('ready', () => {
            new EditorComponent();
            liveEditComponent();
            mw.app.editor.on('editNodeRequest',function(element){
                 
                element.contentEditable = true;
                mw.app.richTextEditor.smallEditorInteract(element)
 
                 
            
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

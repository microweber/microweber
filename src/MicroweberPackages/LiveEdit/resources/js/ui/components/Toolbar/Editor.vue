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
        });
    }
}
</script>

<template>
    <div>
    <div class="toolbar-nav" id="mw-live-edit-editor"></div>
    </div>
</template>

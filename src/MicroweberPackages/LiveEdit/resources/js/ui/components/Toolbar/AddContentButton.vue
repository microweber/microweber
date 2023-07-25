<template>
    <button v-on:click="openContentAddModal()" class=" btn btn-icon me-2 live-edit-toolbar-buttons live-edit-toolbar-buttons-undo-redo tblr-body-color">
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M434.5-434.5H191.869v-91H434.5v-242.631h91V-525.5h242.631v91H525.5v242.631h-91V-434.5Z"/></svg>    </button>

</template>

<script>
export default {
    methods: {

        openContentAddModal: function () {
            var moduleType = 'editor/add_content_modal';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_add_content_modal';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);



            var modal = mw.dialogIframe({
                url: src,
                width: '70%',
                name: 'mw-add-content-editor-front',
                title: 'Add content',
                template: 'default',
                center: false,
                resize: true,
                autosize: true,
                autoHeight: true,
                draggable: true
            });

            this.contentAddModalInstance = modal;
        },
    },
    data() {
        return {
            contentAddModalInstance: null
        };
    },
    mounted() {


    },

}
</script>

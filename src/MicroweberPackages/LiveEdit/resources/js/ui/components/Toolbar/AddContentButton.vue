<template>
    <button v-on:click="openContentAddModal()" class=" btn btn-icon me-2 live-edit-toolbar-buttons">
        + Add Content
    </button>

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
                // width: 500,
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

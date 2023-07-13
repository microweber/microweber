<template>
    <h4>Tools</h4>

    <button v-on:click="show('style-editor')">Open CSS Editor</button>
    <button v-on:click="show('html-editor')">Open html Editor</button>
    <button v-on:click="openContentRevisionsDialog()">openContentRevisionsDialog</button>
    <button onclick="mw.tools.open_reset_content_editor();">open_reset_content_editor</button>
     Open html editor<br>
    clear cache<br>
    content revisions<br>
</template>


<script>


export default {
    components: {},
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        },
        openContentRevisionsDialog: function () {
var cont_id = mw.url.windowHashParam('content_id');
            if (typeof (cont_id) === 'undefined') {
                return;
            }


            var moduleType = 'editor/content_revisions/list_for_content';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_admin_content_revisions_list_for_content_popup_modal_module';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            var dlg = mw.top().dialogIframe({
                // url: mw.settings.site_url + 'api/module?id=mw_global_html_editor&live_edit=true&module_settings=true&type=editor/code_editor&autosize=true',
                url: src,
                title: mw.lang('Edit Code'),
                footer: false,
                width: 400,
                //  height: 600,
                height: 'auto',
                autoHeight: true,
                overlay: false
            });



        },
    },
    mounted() {

    },
    data() {

    }
}
</script>





<template>
    <h4>Tools</h4>
    <div class="d-grid gap-2">

        <button class="btn btn-outline-secondary btn-sm" v-on:click="show('style-editor')">Open CSS Editor</button>
        <button class="btn btn-outline-secondary btn-sm" v-on:click="show('html-editor')">Open Code Editor</button>
        <button class="btn btn-outline-secondary btn-sm" v-on:click="openContentResetContent()">Reset Content</button>
        <button class="btn btn-outline-secondary btn-sm" v-on:click="openContentRevisionsDialog()">Content Revisions
        </button>
        <button class="btn btn-outline-secondary btn-sm" v-on:click="clearCache()">Clear Cache</button>
    </div>

</template>


<script>


export default {
    components: {},
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        },

        clearCache: function () {
            mw.tools.confirm("Do you want to clear cache?", function () {
                mw.notification.warning("Clearing cache...");
                $.get(mw.settings.api_url + "clearcache", {}, function () {
                    mw.notification.warning("Cache is cleared! reloading the page...");
                    location.reload();
                });
            });
        },
        openContentResetContent: function () {
            var moduleType = 'editor/reset_content';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_reset_content_editor';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);


            if (typeof (root_element_id) != 'undefined') {
                var src = src + '&root_element_id=' + root_element_id;
            }

            // mw.dialogIframe({
            var modal = mw.dialogIframe({
                url: src,
                // width: 500,
                // height: mw.$(window).height() - (2.5 * mw.tools.TemplateSettingsModalDefaults.top),
                name: 'mw-reset-content-editor-front',
                title: 'Reset content',
                template: 'default',
                center: false,
                resize: true,
                autosize: true,
                autoHeight: true,
                draggable: true
            });
        },
        openContentRevisionsDialog: function () {

            var liveEditIframeData = mw.app.canvas.getLiveEditData();

            if (liveEditIframeData
                && liveEditIframeData.content
                && liveEditIframeData.content.id
                && liveEditIframeData.content.title
            ) {
                var cont_id = liveEditIframeData.content.id;
            }


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

            attrsForSettings.content_id = cont_id;

            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            var dlg = mw.top().dialogIframe({
                url: src,
                title: mw.lang('Content Revisions'),
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





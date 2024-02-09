<template>
    <div class="p-3">
        <label class="font-weight-bold fs-2 mt-2 mb-2">Tools</label>
        <ul class="d-grid gap-2 list-unstyled">

            <li>
                <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="show('style-editor')">
                    <svg class="mb-2 me-1"
                        xmlns="http://www.w3.org/2000/svg" height="22"
                        viewBox="0 -960 960 960" width="22">
                        <path d="M480-120q-133 0-226.5-92T160-436q0-65 25-121.5T254-658l226-222 226 222q44 44 69 100.5T800-436q0 132-93.5 224T480-120ZM242-400h474q12-72-13.5-123T650-600L480-768 310-600q-27 26-53 77t-15 123Z"/>
                    </svg>
                    Element Style Editor
                </a>
            </li>

            <li>
                <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="showCodeEditor()">

                    <svg class="mb-2 me-1" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 -960 960 960" width="22"><path d="M0-360v-240h60v80h80v-80h60v240h-60v-100H60v100H0Zm310 0v-180h-70v-60h200v60h-70v180h-60Zm170 0v-200q0-17 11.5-28.5T520-600h180q17 0 28.5 11.5T740-560v200h-60v-180h-40v140h-60v-140h-40v180h-60Zm320 0v-240h60v180h100v60H800Z"/></svg>
                    Code Editor
                </a>
            </li>

<!--            <li>-->
<!--                <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="editStylesheetVariablesInEditor()">-->
<!--                    <svg class="mb-2 me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22"-->
<!--                         viewBox="0 -960 960 960" width="22">-->
<!--                        <path-->
<!--                            d="M320-242 80-482l242-242 43 43-199 199 197 197-43 43Zm318 2-43-43 199-199-197-197 43-43 240 240-242 242Z"/>-->
<!--                    </svg>-->
<!--                    Css Variables Editor-->
<!--                </a>-->
<!--            </li>-->

<!--            <li>-->
<!--                <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="openContentRevisionsDialog()">-->
<!--                    <svg class="mb-2 me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22"-->
<!--                         viewBox="0 -960 960 960" width="22">-->
<!--                        <path-->
<!--                            d="M490-526h60v-84h84v-60h-84v-84h-60v84h-84v60h84v84Zm-84 156h228v-60H406v60ZM260-160q-24 0-42-18t-18-42v-640q0-24 18-42t42-18h348l232 232v468q0 24-18 42t-42 18H260Zm0-60h520v-442L578-860H260v640ZM140-40q-24 0-42-18t-18-42v-619h60v619h498v60H140Zm120-180v-640 640Z"/>-->
<!--                    </svg>-->
<!--                    Content Revisions-->
<!--                </a>-->
<!--            </li>-->

            <li>
                <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="openContentResetContent()">
                    <svg class="mb-2 me-1" xmlns="http://www.w3.org/2000/svg"
                         height="22" viewBox="0 -960 960 960" width="22">
                        <path d="M204-318q-22-38-33-78t-11-82q0-134 93-228t227-94h7l-64-64 56-56 160 160-160 160-56-56 64-64h-7q-100 0-170 70.5T240-478q0 26 6 51t18 49l-60 60ZM481-40 321-200l160-160 56 56-64 64h7q100 0 170-70.5T720-482q0-26-6-51t-18-49l60-60q22 38 33 78t11 82q0 134-93 228t-227 94h-7l64 64-56 56Z"/>
                    </svg>
                    Reset Content
                </a>
            </li>

            <li>
                <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="clearCache()">
                    <svg class="mb-2 me-1" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 -960 960 960" width="22"><path d="M280-720v520-520Zm170 600H280q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v172q-17-5-39.5-8.5T680-560v-160H280v520h132q6 21 16 41.5t22 38.5Zm-90-160h40q0-63 20-103.5l20-40.5v-216h-80v360Zm160-230q17-11 38.5-22t41.5-16v-92h-80v130ZM680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm66-106 28-28-74-74v-112h-40v128l86 86Z"/></svg>
                    Clear Cache
                </a>
            </li>
        </ul>
    </div>
</template>


<script>


export default {
    components: {},
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        },
        showCodeEditor: function () {
            this.emitter.emit('show-code-editor');
        },
        hideContentRevisionsDialog: function () {
            if (this.contentRevisionsDialogInstance) {
                this.contentRevisionsDialogInstance.remove();
                this.contentRevisionsDialogInstance = null;
            }
        },
        hideContentResetDialog: function () {
            if (this.contentResetContentInstance) {
                this.contentResetContentInstance.remove();
                this.contentResetContentInstance = null;
            }
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

            this.contentResetContentInstance = modal;
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

            if (liveEditIframeData
                && liveEditIframeData.content
                && liveEditIframeData.content.id
                && liveEditIframeData.content.title
                && liveEditIframeData.content.is_home
                && liveEditIframeData.content.is_home === '1'
            ) {
                attrsForSettings.from_url_string_home = 1;
            } else {
                attrsForSettings.from_url_string = mw.app.canvas.getWindow().location.href;
            }

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
                overlay: false,

            });

            this.contentRevisionsDialogInstance = dlg;

        },

        editStylesheetVariablesInEditor() {

            if (mw.top().app.cssEditor) {
                var vals = mw.top().app.cssEditor.getThemeCSSVariablesAsText();
                if (vals) {

                    var btn = document.createElement('button');
                    btn.className = 'btn btn-outline-primary w-100';
                    btn.innerHTML = mw.lang('Apply CSS Variables');
                    btn.onclick = function (ev) {
                        var newVals = mw.top().doc.getElementById('cssVariablesTextarea').value;
                        mw.top().app.cssEditor.applyThemeCSSVariablesFromText(newVals);
                    };


                    let cssVariablesDialog = mw.top().dialog({
                        content: '<div style="width:100%;height:400px;padding:20px;">' +

                            '<textarea id="cssVariablesTextarea" style="width:100%;height:100%;border:none;resize:none;">' + vals + '</textarea>' +

                            '</div>',
                        title: 'CSS Variables',
                        footer: btn,
                        width: 480,
                        overlayClose: true,
                    });
                    return cssVariablesDialog

                } else {
                    alert('No CSS Variables found');
                }
            }
        },
    },
    mounted() {
        var toolButtonsInstance = this;
        // close open tools when page is changed
        mw.app.canvas.on('liveEditCanvasBeforeUnload', function () {
            toolButtonsInstance.hideContentRevisionsDialog();
            toolButtonsInstance.hideContentResetDialog();
        });

        mw.app.canvas.on('liveEditCanvasLoaded', function () {
            mw.app.editor.on('insertLayoutRequest', function (element) {
                // close open tools when layout is inserted
                toolButtonsInstance.hideContentRevisionsDialog();
                toolButtonsInstance.hideContentResetDialog();
            });
            mw.app.editor.on('insertModuleRequest', function (element) {
                // close open tools when module is inserted
                toolButtonsInstance.hideContentRevisionsDialog();
                toolButtonsInstance.hideContentResetDialog();
            });
        });


    },
    data() {
        return {
            contentRevisionsDialogInstance: null,
            contentResetContentInstance: null,
        }

    }
}
</script>





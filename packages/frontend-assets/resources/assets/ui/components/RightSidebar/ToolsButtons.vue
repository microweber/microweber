<template>
    <ul class="d-grid gap-3 list-unstyled p-3 pb-0">
        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="showCodeEditor()">
                <svg fill="currentColor" class="mb-1 me-2" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                    <path
                        d="M0-360v-240h60v80h80v-80h60v240h-60v-100H60v100H0Zm310 0v-180h-70v-60h200v60h-70v180h-60Zm170 0v-200q0-17 11.5-28.5T520-600h180q17 0 28.5 11.5T740-560v200h-60v-180h-40v140h-60v-140h-40v180h-60Zm320 0v-240h60v180h100v60H800Z"/>
                </svg>
                <Lang>Code Editor</Lang>
            </a>
        </li>

        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="handleLayers()">
                <svg fill="currentColor" class="mb-1 me-2" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                <path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"/>
            </svg>
                <Lang>Layers</Lang>
            </a>
        </li>

        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="openContentResetContent()">
                <svg fill="currentColor" class="mb-1 me-2" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"><path d="M440-122q-121-15-200.5-105.5T160-440q0-66 26-126.5T260-672l57 57q-38 34-57.5 79T240-440q0 88 56 155.5T440-202v80Zm80 0v-80q87-16 143.5-83T720-440q0-100-70-170t-170-70h-3l44 44-56 56-140-140 140-140 56 56-44 44h3q134 0 227 93t93 227q0 121-79.5 211.5T520-122Z"/></svg>
                <Lang>Reset Content</Lang>
            </a>
        </li>

        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="clearCache()">
                <svg fill="currentColor" class="mb-1 me-2" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px">
                    <path
                        d="M280-720v520-520Zm170 600H280q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v172q-17-5-39.5-8.5T680-560v-160H280v520h132q6 21 16 41.5t22 38.5Zm-90-160h40q0-63 20-103.5l20-40.5v-216h-80v360Zm160-230q17-11 38.5-22t41.5-16v-92h-80v130ZM680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm66-106 28-28-74-74v-112h-40v128l86 86Z"/>
                </svg>
                <Lang>Clear Cache</Lang>
            </a>
        </li>

    </ul>
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
            mw.confirm("Do you want to clear cache?", function () {
                mw.notification.warning("Clearing cache...");
                $.get(mw.settings.api_url + "clearcache", {}, function () {
                    mw.notification.warning("Cache is cleared! reloading the page...");
                    location.reload();
                });
            });
        },

        handleLayers: function () {
            this.layers = !this.layers;
            mw.app.liveEditWidgets.toggleLayers();

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


          //  var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            var src = mw.app.adminModules.getModuleSettingsUrl(moduleType, attrsForSettings);



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

            if (mw.top().app.themeCssVarsEditor) {
                var vals = mw.top().app.themeCssVarsEditor.getThemeCSSVariablesAsText();
                if (vals) {

                    var btn = document.createElement('button');
                    btn.className = 'btn btn-outline-primary w-100';
                    btn.innerHTML = mw.lang('Apply CSS Variables');
                    btn.onclick = function (ev) {
                        var newVals = mw.top().doc.getElementById('cssVariablesTextarea').value;
                        mw.top().app.themeCssVarsEditor.applyThemeCSSVariablesFromText(newVals);
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
        mw.top().app.on('DOMTreeReady', () => {
            mw.top().app.domTree.on('show', () => {
                this.layers = true
            });
            mw.top().app.domTree.on('hide', () => {
                this.layers = false
            });
        });

    },
    data() {
        return {
            contentRevisionsDialogInstance: null,
            contentResetContentInstance: null,
            layers: false,
        }

    }
}
</script>





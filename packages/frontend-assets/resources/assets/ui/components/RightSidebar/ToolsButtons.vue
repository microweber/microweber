<template>
    <ul class="d-grid gap-3 list-unstyled p-3 pb-0">
        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="showCodeEditor()">

                <svg class="mb-1 me-1" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px">
                    <path
                        d="M0-360v-240h60v80h80v-80h60v240h-60v-100H60v100H0Zm310 0v-180h-70v-60h200v60h-70v180h-60Zm170 0v-200q0-17 11.5-28.5T520-600h180q17 0 28.5 11.5T740-560v200h-60v-180h-40v140h-60v-140h-40v180h-60Zm320 0v-240h60v180h100v60H800Z"/>
                </svg>
                Code Editor
            </a>
        </li>

        <li>

            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="handleLayers()">
                <svg class="mb-1 me-1" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="currentColor">
                <path d="M480-400 40-640l440-240 440 240-440 240Zm0 160L63-467l84-46 333 182 333-182 84 46-417 227Zm0 160L63-307l84-46 333 182 333-182 84 46L480-80Zm0-411 273-149-273-149-273 149 273 149Zm0-149Z"/>
            </svg>
                Layers
            </a>

        </li>
        <li>

<a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="handleQuickEdit()">
    <svg class="mb-1 me-1" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px" fill="#5f6368"><path d="M240-80v-172q-57-52-88.5-121.5T120-520q0-150 105-255t255-105q125 0 221.5 73.5T827-615l52 205q5 19-7 34.5T840-360h-80v120q0 33-23.5 56.5T680-160h-80v80h-80v-160h160v-200h108l-38-155q-23-91-98-148t-172-57q-116 0-198 81t-82 197q0 60 24.5 114t69.5 96l26 24v208h-80Zm254-360Zm-54 80h80l6-50q8-3 14.5-7t11.5-9l46 20 40-68-40-30q2-8 2-16t-2-16l40-30-40-68-46 20q-5-5-11.5-9t-14.5-7l-6-50h-80l-6 50q-8 3-14.5 7t-11.5 9l-46-20-40 68 40 30q-2 8-2 16t2 16l-40 30 40 68 46-20q5 5 11.5 9t14.5 7l6 50Zm40-100q-25 0-42.5-17.5T420-520q0-25 17.5-42.5T480-580q25 0 42.5 17.5T540-520q0 25-17.5 42.5T480-460Z"/></svg>
    Quick Ai edit
</a>

</li>
        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="openContentResetContent()">
                <svg class="mb-1 me-1" xmlns="http://www.w3.org/2000/svg"
                     height="18" viewBox="0 -960 960 960" width="18">
                    <path
                        d="M204-318q-22-38-33-78t-11-82q0-134 93-228t227-94h7l-64-64 56-56 160 160-160 160-56-56 64-64h-7q-100 0-170 70.5T240-478q0 26 6 51t18 49l-60 60ZM481-40 321-200l160-160 56 56-64 64h7q100 0 170-70.5T720-482q0-26-6-51t-18-49l60-60q22 38 33 78t11 82q0 134-93 228t-227 94h-7l64 64-56 56Z"/>
                </svg>
                Reset Content
            </a>
        </li>

        <li>
            <a class="mw-admin-action-links mw-adm-liveedit-tabs" v-on:click="clearCache()">
                <svg class="mb-1 me-1" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960" width="18px">
                    <path
                        d="M280-720v520-520Zm170 600H280q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v172q-17-5-39.5-8.5T680-560v-160H280v520h132q6 21 16 41.5t22 38.5Zm-90-160h40q0-63 20-103.5l20-40.5v-216h-80v360Zm160-230q17-11 38.5-22t41.5-16v-92h-80v130ZM680-80q-83 0-141.5-58.5T480-280q0-83 58.5-141.5T680-480q83 0 141.5 58.5T880-280q0 83-58.5 141.5T680-80Zm66-106 28-28-74-74v-112h-40v128l86 86Z"/>
                </svg>
                Clear Cache
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
        handleQuickEdit: function () {
            mw.app.liveEditWidgets.toggleQuickEditComponent()
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





<template>
    <div class="p-3">
        <label class="font-weight-bold fs-2 mt-2 mb-2">Tools</label>
        <ul class="d-grid gap-2 list-unstyled">
            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="show('template-settings')">

                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960"
                         width="22">
                        <path
                            d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 32.5-156t88-127Q256 239 330 207.5T488 176q80 0 151 27.5t124.5 76q53.5 48.5 85 115T880 538q0 115-70 176.5T640 776h-74q-9 0-12.5 5t-3.5 11q0 12 15 34.5t15 51.5q0 50-27.5 74T480 976Zm0-400Zm-220 40q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120-160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm200 0q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm120 160q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17ZM480 896q9 0 14.5-5t5.5-13q0-14-15-33t-15-57q0-42 29-67t71-25h70q66 0 113-38.5T800 538q0-121-92.5-201.5T488 256q-136 0-232 93t-96 227q0 133 93.5 226.5T480 896Z"/>
                    </svg>

                    Template Settings
                </a>
            </li>
            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="show('style-editor')">
                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="25"
                         viewBox="0 -960 960 960" width="25">
                        <path
                            d="M414-360q-15 0-24.5-9.5T380-394v-46h48v32h104v-53H414q-14 0-24-10t-10-24v-71q0-15 9.5-24.5T414-600h132q15 0 24.5 9.5T580-566v46h-48v-32H428v53h118q14 0 24 10t10 24v71q0 15-9.5 24.5T546-360H414Zm260 0q-15 0-24.5-9.5T640-394v-46h48v32h104v-53H674q-14 0-24-10t-10-24v-71q0-15 9.5-24.5T674-600h132q15 0 24.5 9.5T840-566v46h-48v-32H688v53h118q14 0 24 10t10 24v71q0 15-9.5 24.5T806-360H674Zm-520 0q-15 0-24.5-9.5T120-394v-172q0-15 9.5-24.5T154-600h132q15 0 24.5 9.5T320-566v46h-48v-32H168v144h104v-32h48v46q0 15-9.5 24.5T286-360H154Z"/>
                    </svg>
                    Element Style Editor
                </a>
            </li>

            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="show('html-editor')">

                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 -960 960 960" width="24">
                        <path
                            d="M320-242 80-482l242-242 43 43-199 199 197 197-43 43Zm318 2-43-43 199-199-197-197 43-43 240 240-242 242Z"/>
                    </svg>
                    Open Code Editor
                </a>
            </li>

            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="editStylesheetVariablesInEditor()">
                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 -960 960 960" width="24">
                        <path
                            d="M320-242 80-482l242-242 43 43-199 199 197 197-43 43Zm318 2-43-43 199-199-197-197 43-43 240 240-242 242Z"/>
                    </svg>
                                       Css Variables Editor
                </a>
            </li>




            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="openContentRevisionsDialog()">
                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 -960 960 960" width="24">
                        <path
                            d="M490-526h60v-84h84v-60h-84v-84h-60v84h-84v60h84v84Zm-84 156h228v-60H406v60ZM260-160q-24 0-42-18t-18-42v-640q0-24 18-42t42-18h348l232 232v468q0 24-18 42t-42 18H260Zm0-60h520v-442L578-860H260v640ZM140-40q-24 0-42-18t-18-42v-619h60v619h498v60H140Zm120-180v-640 640Z"/>
                    </svg>
                    Content Revisions
                </a>
            </li>

            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="openContentResetContent()">
                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 -960 960 960" width="24">
                        <path
                            d="M477-120q-149 0-253-105.5T120-481h60q0 125 86 213t211 88q127 0 215-89t88-216q0-124-89-209.5T477-780q-68 0-127.5 31T246-667h105v60H142v-208h60v106q52-61 123.5-96T477-840q75 0 141 28t115.5 76.5Q783-687 811.5-622T840-482q0 75-28.5 141t-78 115Q684-177 618-148.5T477-120Zm128-197L451-469v-214h60v189l137 134-43 43Z"/>
                    </svg>
                    Reset Content
                </a>
            </li>

            <li>
                <a class="btn btn-outline-secondary w-100" v-on:click="clearCache()">
                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 -960 960 960" width="24">
                        <path
                            d="M120-40v-290q0-78.85 56-134.425Q232-520 310-520h60v-340q0-24.75 17.625-42.375T430-920h100q24.75 0 42.375 17.625T590-860v340h60q78.85 0 134.425 55.575Q840-408.85 840-330v290H120Zm60-60h105v-130q0-12.75 9-21.375T315.5-260q12.5 0 21 8.625T345-230v130h105v-130q0-12.75 9-21.375T480.5-260q12.5 0 21 8.625T510-230v130h105v-130q0-12.75 9-21.375T645.5-260q12.5 0 21 8.625T675-230v130h105v-230q0-54.167-37.917-92.083Q704.167-460 650-460H310q-54.167 0-92.083 37.917Q180-384.167 180-330v230Zm350-420v-340H430v340h100Z"/>
                    </svg>
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





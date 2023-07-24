<template>
    <div class="p-3">
        <label class="font-weight-bold fs-2 mt-2 mb-2">Tools</label>
        <ul class="d-grid gap-2 list-unstyled">
           <li class="pb-2">
               <a class="live-edit-tools" v-on:click="show('html-editor')">

                   <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                        viewBox="0 -960 960 960" width="24">
                       <path
                           d="M320-242 80-482l242-242 43 43-199 199 197 197-43 43Zm318 2-43-43 199-199-197-197 43-43 240 240-242 242Z"/>
                   </svg>
                   Open Code Editor
               </a>
           </li>

          <li class="py-2">
              <a class="live-edit-tools" v-on:click="show('style-editor')">
                  <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="25"
                       viewBox="0 -960 960 960" width="25">
                      <path
                          d="M414-360q-15 0-24.5-9.5T380-394v-46h48v32h104v-53H414q-14 0-24-10t-10-24v-71q0-15 9.5-24.5T414-600h132q15 0 24.5 9.5T580-566v46h-48v-32H428v53h118q14 0 24 10t10 24v71q0 15-9.5 24.5T546-360H414Zm260 0q-15 0-24.5-9.5T640-394v-46h48v32h104v-53H674q-14 0-24-10t-10-24v-71q0-15 9.5-24.5T674-600h132q15 0 24.5 9.5T840-566v46h-48v-32H688v53h118q14 0 24 10t10 24v71q0 15-9.5 24.5T806-360H674Zm-520 0q-15 0-24.5-9.5T120-394v-172q0-15 9.5-24.5T154-600h132q15 0 24.5 9.5T320-566v46h-48v-32H168v144h104v-32h48v46q0 15-9.5 24.5T286-360H154Z"/>
                  </svg>
                  Open CSS Editor
              </a>
          </li>

            <li class="pb-2">
                <a class="live-edit-tools" v-on:click="openContentRevisionsDialog()">
                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                         viewBox="0 -960 960 960" width="24">
                        <path
                            d="M490-526h60v-84h84v-60h-84v-84h-60v84h-84v60h84v84Zm-84 156h228v-60H406v60ZM260-160q-24 0-42-18t-18-42v-640q0-24 18-42t42-18h348l232 232v468q0 24-18 42t-42 18H260Zm0-60h520v-442L578-860H260v640ZM140-40q-24 0-42-18t-18-42v-619h60v619h498v60H140Zm120-180v-640 640Z"/>
                    </svg>
                    Content Revisions
                </a>
            </li>

           <li class="pb-2">
               <a class="live-edit-tools" v-on:click="openContentResetContent()">
                   <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24"
                        viewBox="0 -960 960 960" width="24">
                       <path
                           d="M477-120q-149 0-253-105.5T120-481h60q0 125 86 213t211 88q127 0 215-89t88-216q0-124-89-209.5T477-780q-68 0-127.5 31T246-667h105v60H142v-208h60v106q52-61 123.5-96T477-840q75 0 141 28t115.5 76.5Q783-687 811.5-622T840-482q0 75-28.5 141t-78 115Q684-177 618-148.5T477-120Zm128-197L451-469v-214h60v189l137 134-43 43Z"/>
                   </svg>
                   Reset Content
               </a>
           </li>

            <li class="pb-2">
                <a class="live-edit-tools" v-on:click="clearCache()">
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

            this.contentRevisionsDialogInstance = dlg;

        },
    },
    mounted() {





    },
    data() {
        return {
            contentRevisionsDialogInstance: null,
            contentResetContentInstance: null,
        }

    }
}
</script>





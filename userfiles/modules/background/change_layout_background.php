<script>
    class FilePickerComponent extends {

        constructor(options = {}) {
            const defaults = {
                canEdit: true,
                canDelete: true,
                element: null,
                template: null,
                document,
            }
            this.settings = Object.assign({}, defaults, options);
            this.document = this.settings.document;
            this.element = this.settings.element;
            if(typeof this.element === 'string') {
                this.element = this.document.querySelector(this.settings.element);
            }
            this.window = this.settings.document.defaultView;
        }

        template() {
            const id = mw.id();
            const template = this.settings.template || `
                <div class="mw-filepicker-component">
                    <div id="js-preview-image-wrapper-${id}" style="display:none">
                        <div class="d-flex justify-content-between">
                            <div>
                                <img src="" style="border-radius:4px" class="js-select-file-${id} cursor-pointer" id="js-preview-image-${id}" />
                            </div>
                            <div class="d-flex gap-2">
                                <x-microweber-ui::button-action type="button" class="btn-sm js-select-file-${id}">
                                    Change
                                </x-microweber-ui::button-action>
                                <x-microweber-ui::button-action type="button" id="js-edit-image-${id}" style="display:none" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"> <path d="M22.7 14.3L21.7 15.3L19.7 13.3L20.7 12.3C20.8 12.2 20.9 12.1 21.1 12.1C21.2 12.1 21.4 12.2 21.5 12.3L22.8 13.6C22.9 13.8 22.9 14.1 22.7 14.3M13 19.9V22H15.1L21.2 15.9L19.2 13.9L13 19.9M11.21 15.83L9.25 13.47L6.5 17H13.12L15.66 14.55L13.96 12.29L11.21 15.83M11 19.9V19.05L11.05 19H5V5H19V11.31L21 9.38V5C21 3.9 20.11 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H11V19.9Z" /></svg>
                                </x-microweber-ui::button-action>

                                <x-microweber-ui::button-action type="button" class="js-remove-file-${id}">
                                    <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                                </x-microweber-ui::button-action>
                            </div>
                        </div>
                    </div>


                    <div id="js-dropzone-image-${id}" class="dropzone mw-dropzone js-select-file-${id}" style="display:none">
                        <div class="d-flex flex-column align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center" style="background:rgba(0,0,0,0.11);color:#000;width:40px;height:40px; border-radius:100%; font-size:28px;">
                                <i class="mdi mdi-plus"></i>
                            </div>
                            <div>
                                <b>
                                    {{ $label }}
                                </b>
                            </div>
                            <div>
                                <span>
                                    <b>20MB Max</b>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            this.element.innerHTML = template;


            return this;
             
        }

        launch() {

        }


        init() {

            if(!this.element) {
                return;
            }

            
            this.template();
            return this;

        }
    } 


    const 

</script>

<div>

    <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" id="bg-tabs">
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100 active">Image</span>
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Video</span>
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100" style="display: none">Color</span>
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Color</span>
    </div>
    <br>

    <div class="bg-tab">

        <div id="bg-image-picker">
            <div class="dropzone mw-dropzone ">
                <div class="d-flex flex-column align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-plus"></i>
                    </div>
                    <div>
                        <b>

                        </b>
                    </div>
                    <div>
                        <span>
                            <b>20MB Max</b>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div id="bg-image-picker-remove-wrapper">
            <button id="bg-image-picker-remove-image"  type="button" class="btn btn-ghost-danger w-100">
                Remove image
            </button>


        </div>



    </div>
    <div class="bg-tab">
        <div id="video-picker">
            <div class="dropzone mw-dropzone ">
                <div class="d-flex flex-column align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-plus"></i>
                    </div>
                    <div>
                        <b>

                        </b>
                    </div>
                    <div>
                                        <span>
                                            <b>20MB Max</b>
                                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div id="video-picker-remove-wrapper">
            <button id="video-picker-remove-video"  type="button" class="btn btn-ghost-danger w-100">
                Remove video
            </button>


        </div>
    </div>
    <div class="bg-tab">
        <div id="color-picker" class="card card-body"></div>

    </div>
    <div class="bg-tab">
        <div id="overlay-color-picker" class="card card-body"></div>

        <div id="overlay-color-picker-remove-wrapper">
            <button id="overlay-color-picker-remove-color"  type="button" class="btn btn-ghost-danger w-100">
                Remove color
            </button>


        </div>
    </div>


    <script>


        var target = mw.top().app.liveEdit.handles.get('layout').getTarget();
        var bg, bgOverlay, bgNode;
        if (target) {


            bg = target.querySelector('.mw-layout-background-block');
            if (bg) {
                var tabLink = document.querySelector('#change-background-tab-link');
                tabLink.style.display = '';
                bgNode = bg.querySelector('.mw-layout-background-node')
                bgOverlay = bg.querySelector('.mw-layout-background-overlay')
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            mw.tabs({
                nav: "#bg-tabs > .btn",
                tabs: ".bg-tab",
                onclick: function () {

                }
            });

            var cp = document.querySelector('#color-picker');

            var picker = mw.colorPicker({
                element: cp,

                mode: 'inline',
                onchange: function (color) {

                    mw.top().app.layoutBackground.setBackgroundColor(bgNode, color);
                    showHideRemoveBackgroundsButtons();
                }
            });

            var cpo = document.querySelector('#overlay-color-picker');

            var cpoPickerPause = false
            var cpoPicker = mw.colorPicker({
                element: cpo,

                mode: 'inline',
                onchange: function (color) {
                    if (!cpoPickerPause) {
                        mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, color);
                        showHideRemoveBackgroundsButtons();
                    }

                }
            });

            if (target && bgOverlay) {
                var color = (getComputedStyle(bgOverlay).backgroundColor);
                if (color == 'rgba(0, 0, 0, 0)') {
                    color = 'rgba(0, 0, 0, 0.5)';
                }
                cpoPickerPause = true;
                cpoPicker.setColor(color);
                cpoPickerPause = false;
            }


            const bgImagePicker = document.querySelector('#bg-image-picker')

            bgImagePicker.addEventListener('click', function () {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'images',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if (!url) {
                            dialog.remove();
                            return
                        }
                        url = url.toString();

                        mw.top().app.layoutBackground.setBackgroundImage(bgNode, url);
                        dialog.remove();

                        showHideRemoveBackgroundsButtons();
                    }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select image'),
                    footer: false,
                    width: 860,


                });
                picker.$cancel.on('click', function () {
                    dialog.remove()
                })
            })
            document.querySelector('#video-picker').addEventListener('click', function () {
                var dialog;
                var picker = new mw.filePicker({
                    type: 'videos',
                    label: false,
                    autoSelect: false,
                    footer: true,
                    _frameMaxHeight: true,
                    onResult: function (res) {
                        var url = res.src ? res.src : res;
                        if (!url) {
                            dialog.remove();
                            return
                        }
                        url = url.toString();
                        mw.top().app.layoutBackground.setBackgroundVideo(bgNode, url);
                        dialog.remove();
                        showHideRemoveBackgroundsButtons();
                   }
                });
                dialog = mw.top().dialog({
                    content: picker.root,
                    title: mw.lang('Select video'),
                    footer: false,
                    width: 860,


                });
                picker.$cancel.on('click', function () {
                    dialog.remove()
                })
            })


            document.querySelector('#video-picker-remove-video').addEventListener('click', function () {
                mw.top().app.layoutBackground.setBackgroundVideo(bgNode, '');
                showHideRemoveBackgroundsButtons()
            })
            document.querySelector('#bg-image-picker-remove-image').addEventListener('click', function () {
                mw.top().app.layoutBackground.setBackgroundImage(bgNode, '');
                showHideRemoveBackgroundsButtons()
            })

            document.querySelector('#overlay-color-picker-remove-color').addEventListener('click', function () {

                mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, '');
                showHideRemoveBackgroundsButtons()
            })
            showHideRemoveBackgroundsButtons();
        });


        function showHideRemoveBackgroundsButtons(){
            var hasBgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);
            if(hasBgImage){
                $('#bg-image-picker-remove-image').show()
            } else {
                $('#bg-image-picker-remove-image').hide()

            }

            var hasBgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
            if(hasBgVideo){
                $('#video-picker-remove-video').show()
            } else {
                $('#video-picker-remove-video').hide()
            }

            var hasBgColor = mw.top().app.layoutBackground.getBackgroundColor(bgOverlay)
            if(hasBgColor){
                $('#overlay-color-picker-remove-color').show()
            } else {
                $('#overlay-color-picker-remove-color').hide()
            }

        }

    </script>

</div>

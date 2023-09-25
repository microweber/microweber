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
                        <div id="bg-image-picker-preview-image">

                        </div>
                        <div id="bg-image-picker-upload-text">
                            <span>
                                <b>20MB Max</b>
                            </span>
                        </div>
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
                    <div id="bg-video-picker-preview-video">

                    </div>
                    <div id="bg-video-picker-preview-text">
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

                $('#bg-image-picker-preview-image').html('<img src="'+hasBgImage+'" style="max-width: 100%; max-height: 100%">')
                $('#bg-image-picker-preview-image').show()
                $('#bg-image-picker-remove-image').show()
                $('#bg-image-picker-upload-text').hide()

            } else {
                $('#bg-image-picker-upload-text').show()
                $('#bg-image-picker-preview-image').hide()
                $('#bg-image-picker-remove-image').hide()

            }

            var hasBgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
            if(hasBgVideo){
                $('#bg-video-picker-preview-video').html('<video src="'+hasBgVideo+'" style="max-width: 100%; max-height: 100%" autoplay muted loop></video>')
                $('#bg-video-picker-preview-video').show()
                $('#video-picker-remove-video').show()
                $('#bg-video-picker-preview-text').hide()
            } else {
                $('#bg-video-picker-preview-text').show()
                $('#video-picker-remove-video').hide()
                $('#bg-video-picker-preview-video').hide()
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

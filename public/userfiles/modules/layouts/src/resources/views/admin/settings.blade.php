<x-filament::section>
    <x-slot name="heading">
        Layout settings
    </x-slot>

    <div  id="change-background" wire:ignore>

        <style>
            .change-layout-background-wrapper span {
                font-size: 12px;

            }

            .change-layout-background-wrapper {
                max-width: 90%;
            }
        </style>

        <script>
            $(document).ready(function() {
                $('.change-layout-background-wrapper span').on('click', function() {
                    // Remove 'active' class from all spans
                    $('.change-layout-background-wrapper span').removeClass('active');
                    // Add 'active' class to the clicked span
                    $(this).addClass('active');
                });
            });
        </script>

        <div>

            <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" id="bg-tabs">
                <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100 active js-filepicker-pick-type-tab-image">Image</span>
                <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100 js-filepicker-pick-type-tab-video">Video</span>
                <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100 js-filepicker-pick-type-tab-color">Color</span>
                <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100" style="display: none;" id="change-background-tab-link">Other</span>
            </div>
            <br>

            <div class="bg-tab">

                <div id="bg--image-picker">

                </div>


                <br>
                <div class="change-layout-background-wrapper">
                    <label class="live-edit-label">Image size</label>
                    <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper mx-0">
                        <label class="form-selectgroup-item w-100">
                            <input type="radio" name="backgroundSize" value="auto" class="form-selectgroup-input" checked />
                            <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Auto</span>
                        </label>
                        <label class="form-selectgroup-item w-100">
                            <input type="radio" name="backgroundSize" value="cover" class="form-selectgroup-input" />
                            <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Cover</span>
                        </label>
                        <label class="form-selectgroup-item w-100">
                            <input type="radio" name="backgroundSize" value="contain" class="form-selectgroup-input" />
                            <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Fit</span>
                        </label>
                        <label class="form-selectgroup-item w-100">
                            <input type="radio" name="backgroundSize" value="100% 100%" class="form-selectgroup-input" />
                            <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Scale</span>
                        </label>
                    </div>
                </div>




            </div>
            <div class="bg-tab">

                <div id="bg--video-picker">

                </div>

            </div>

            <div class="bg-tab">
                <div id="overlay-color-picker" class="card card-body"></div>

                <div id="overlay-color-picker-remove-wrapper">
                    <button id="overlay-color-picker-remove-color"  type="button" class="btn btn-ghost-danger w-100">
                        Remove color
                    </button>


                </div>
            </div>





            <div class="bg-tab">

                <h4>Other settings</h4>

                Cursor image, must be small image, for example 32x32px

                <div id="bg--cursor-picker">

                </div>



            </div>


            <script>



                $(document).ready(function () {
                    mw.top().app.on('onModuleReloaded', function (moduleId) {
                        handleReady();
                    });

                });




                const getTargets = () => {
                    const target = mw.top().app.liveEdit.handles.get('layout').getTarget();
                    let bg, bgOverlay, bgNode;
                    if (target) {


                        bg = target.querySelector('.mw-layout-background-block');
                        if (bg) {

                            bgNode = bg.querySelector('.mw-layout-background-node')
                            bgOverlay = bg.querySelector('.mw-layout-background-overlay')
                        }
                        var tabLink = document.querySelector('#change-background-tab-link');

                        if (target && bg) {
                            if(tabLink){
                                tabLink.style.display = '';
                            }
                        } else {
                            if(tabLink) {
                                tabLink.style.display = 'none';
                            }
                        }
                    }
                    return {bg, bgOverlay, bgNode, target}

                }





                let picker, videoPicker;
                const handleReady = () => {
                    let {bg, bgOverlay, bgNode, target} = getTargets();

                    let bgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);
                    let bgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode);
                    let bgCursor = mw.top().app.layoutBackground.getBackgroundCursor(bgNode);
                    let bgSize = mw.top().app.layoutBackground.getBackgroundImageSize(bgNode);
                    if(!bgSize) {
                        bgSize = 'auto' ;
                    }

                    document.querySelectorAll('[name="backgroundSize"]').forEach(function (el) {
                        el.checked = el.value === bgSize;
                        el.addEventListener('change', function () {
                            const {bg, bgOverlay, bgNode, target} = getTargets();
                            mw.top().app.layoutBackground.setBackgroundImageSize(bgNode, this.value);
                            // mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
                        })
                    });




                    if(!picker) {
                        picker = mw.app.singleFilePickerComponent({
                            element: '#bg--image-picker',
                            accept: 'images',
                            file:  bgImage ? bgImage : null
                        });
                        videoPicker = mw.app.singleFilePickerComponent({
                            element: '#bg--video-picker',
                            accept: 'videos',
                            file:  bgVideo ? bgVideo : null,
                            canEdit: false
                        });


                        cursorPicker = mw.app.singleFilePickerComponent({
                            element: '#bg--cursor-picker',
                            accept: 'images',
                            file:  bgCursor ? bgCursor : null,
                            canEdit: false
                        });
                        cursorPicker.on('change', () => {
                            const {bg, bgOverlay, bgNode, target} = getTargets();
                            mw.top().app.layoutBackground.setBackgroundCursor(target, cursorPicker.file);
                        })

                        picker.on('change', () => {
                            const {bg, bgOverlay, bgNode, target} = getTargets();
                            videoPicker.setFile(null);
                            mw.top().app.layoutBackground.setBackgroundImage(bgNode, picker.file);

                            mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
                        })



                        videoPicker.on('change', () => {
                            const {bg, bgOverlay, bgNode, target} = getTargets();




                            mw.top().app.layoutBackground.setBackgroundVideo(bgNode, videoPicker.file);
                            picker.setFile(null);
                            mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
                        })
                    }


                }


                const handleLayoutTargetChange = function() {
                    handleReady();

                }


                mw.top().app.liveEdit.handles.get('layout').on('targetChange', handleLayoutTargetChange);



                addEventListener('load', () => {


                    handleReady();


                    mw.top().$(mw.top().dialog.get(this.frameElement)).on('Remove', function() {
                        mw.top().app.liveEdit.handles.get('layout').off('targetChange', handleLayoutTargetChange);

                    })

                })


                document.addEventListener("DOMContentLoaded", function () {
                    let {bg, bgOverlay, bgNode, target} = getTargets();
                    mw.tabs({
                        nav: "#bg-tabs > .btn",
                        tabs: ".bg-tab",
                        onclick: function () {

                        }
                    });

                    var cpo = document.querySelector('#overlay-color-picker');

                    var cpoPickerPause = false
                    var cpoPicker = mw.colorPicker({
                        element: cpo,

                        mode: 'inline',
                        onchange: function (color) {
                            let {bg, bgOverlay, bgNode, target} = getTargets();
                            if (!cpoPickerPause) {
                                mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, color);
                                showHideRemoveBackgroundsButtons();
                                mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());
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



                    cpo.querySelectorAll('input').forEach(node => node.addEventListener('keyup', function(e) {

                        if(e.key === 'Escape') {
                            const dialog = mw.dialog.get(this);

                            if(dialog) {
                                dialog.remove();
                            } else if(this.ownerDocument.defaultView.frameElement) {
                                const dialog = mw.dialog.get(this.ownerDocument.defaultView.frameElement);
                                if(dialog) {
                                    dialog.remove();
                                }
                            }
                        }
                    }))



                    document.querySelector('#overlay-color-picker-remove-color').addEventListener('click', function () {
                        let {bg, bgOverlay, bgNode, target} = getTargets();

                        mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, 'transparent');
                        showHideRemoveBackgroundsButtons()
                    })
                    showHideRemoveBackgroundsButtons();
                });


                function showHideRemoveBackgroundsButtons(){

                    let {bg, bgOverlay, bgNode, target} = getTargets();

                    var hasBgColor = mw.top().app.layoutBackground.getBackgroundColor(bgOverlay)
                    if(hasBgColor){
                        $('#overlay-color-picker-remove-color').show()
                    } else {
                        $('#overlay-color-picker-remove-color').hide()
                    }
                    mw.top().app.registerChange(mw.top().app.liveEdit.handles.get('layout').getTarget());

                }

            </script>

        </div>






    </div>


</x-filament::section>

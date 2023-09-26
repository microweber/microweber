 

<div>

    <div class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" id="bg-tabs">
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100 active">Image</span>
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Video</span>
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100" style="display: none">Color</span>
        <span class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">Color</span>
    </div>
    <br>

    <div class="bg-tab">

        <div id="bg--image-picker">
            
        </div>
        

 


    </div>
    <div class="bg-tab">
        
    <div id="bg--video-picker">
            
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

        addEventListener('load', () => {

            const bgImage = mw.top().app.layoutBackground.getBackgroundImage(bgNode);
            const bgVideo = mw.top().app.layoutBackground.getBackgroundVideo(bgNode)

            
            const picker = mw.app.singleFilePickerComponent({
                element: '#bg--image-picker',
                accept: 'images',
                file:  bgImage ? bgImage : null
            });

            picker.on('change', () => {
                videoPicker.setFile(null);
                mw.top().app.layoutBackground.setBackgroundImage(bgNode, picker.file);
            })

            const videoPicker = mw.app.singleFilePickerComponent({
                element: '#bg--video-picker',
                accept: 'videos',
                file:  bgVideo ? bgVideo : null,
                canEdit: false
            });

            videoPicker.on('change', () => {
                mw.top().app.layoutBackground.setBackgroundVideo(bgNode, videoPicker.file);
                picker.setFile(null);
            })
        })


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


 

            document.querySelector('#overlay-color-picker-remove-color').addEventListener('click', function () {

                mw.top().app.layoutBackground.setBackgroundColor(bgOverlay, '');
                showHideRemoveBackgroundsButtons()
            })
            showHideRemoveBackgroundsButtons();
        });


        function showHideRemoveBackgroundsButtons(){
 

            var hasBgColor = mw.top().app.layoutBackground.getBackgroundColor(bgOverlay)
            if(hasBgColor){
                $('#overlay-color-picker-remove-color').show()
            } else {
                $('#overlay-color-picker-remove-color').hide()
            }

        }

    </script>

</div>

@props(['label' => '','allowedType'=>'images'])

<div class="form-control-live-edit-label-wrapper">

    @php

        $attrs = $attributes->merge([]);
        $randId = 'mwfilepickeritem'.md5(rand(111,999).time().uniqid().json_encode($attrs));

    @endphp
    <div>

        <input type="hidden" id="js-media-picker-file-{{$randId}}" {!! $attributes->merge([]) !!} />

        <div id="js-preview-video-wrapper-{{$randId}}" style="display:none">
            <div>
                <div>
                    <video width="100%" height="200px" id="js-preview-video-{{$randId}}" controls=""></video>
                </div>
                <div class="d-flex gap-2">
                    <x-microweber-ui::button-action type="button" class="btn-sm js-select-file-{{$randId}}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>

                    </x-microweber-ui::button-action>

                    <x-microweber-ui::button-action type="button" class="js-remove-file-{{$randId}}">
                        <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                    </x-microweber-ui::button-action>
                </div>
            </div>
        </div>

        <div id="js-preview-image-wrapper-{{$randId}}" style="display:none">
            <div class="d-flex justify-content-between">
                <div>
                    <img src="" style="border-radius:4px; height: 150px; width: 150px; object-fit: contain;" class="js-select-file-{{$randId}} cursor-pointer" id="js-preview-image-{{$randId}}" />
                </div>
                <div class="d-flex gap-2">
                    <x-microweber-ui::button-action type="button" class="btn-sm js-select-file-{{$randId}}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M440-200h80v-167l64 64 56-57-160-160-160 160 57 56 63-63v167ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h320l240 240v480q0 33-23.5 56.5T720-80H240Zm280-520v-200H240v640h480v-440H520ZM240-800v200-200 640-640Z"/></svg>
                    </x-microweber-ui::button-action>
                    <x-microweber-ui::button-action type="button" id="js-edit-image-{{$randId}}" style="display:none" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"> <path d="M22.7 14.3L21.7 15.3L19.7 13.3L20.7 12.3C20.8 12.2 20.9 12.1 21.1 12.1C21.2 12.1 21.4 12.2 21.5 12.3L22.8 13.6C22.9 13.8 22.9 14.1 22.7 14.3M13 19.9V22H15.1L21.2 15.9L19.2 13.9L13 19.9M11.21 15.83L9.25 13.47L6.5 17H13.12L15.66 14.55L13.96 12.29L11.21 15.83M11 19.9V19.05L11.05 19H5V5H19V11.31L21 9.38V5C21 3.9 20.11 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H11V19.9Z" /></svg>
                    </x-microweber-ui::button-action>

                    <x-microweber-ui::button-action type="button" class="js-remove-file-{{$randId}}">
                        <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                    </x-microweber-ui::button-action>
                </div>
            </div>
        </div>


        <div id="js-dropzone-image-{{$randId}}" class="dropzone mw-dropzone js-select-file-{{$randId}}" >
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

        <script>

            function generatePreview{{$randId}}(fullFilePath)
            {
                let imageFileExtensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif',
                    'svg',
                    'webp',
                    'avif',
                    'apng',
                    'bmp',
                    'ico',
                    'cur',
                ];
                let videoFileExtensions = [
                    'mp4',
                    'webm',
                    'ogg',
                    'mov',
                    'avi',
                    'wmv',
                    'flv',
                    '3gp',
                    'mkv',
                ];

                $('#js-preview-image-wrapper-{{$randId}}').hide();
                $('#js-preview-video-wrapper-{{$randId}}').hide();
                $('#js-dropzone-image-{{$randId}}').hide();

               // alert(fullFilePath);

                let showDropzone = true;
                if (fullFilePath.length > 1) {
                    if (imageFileExtensions.includes(fullFilePath.split('.').pop().toLowerCase())) {
                        $('#js-preview-image-{{$randId}}').attr('src', fullFilePath);
                        $('#js-preview-image-wrapper-{{$randId}}').show();
                        $('#js-edit-image-{{$randId}}').show();
                        showDropzone = false;
                    }
                    if (videoFileExtensions.includes(fullFilePath.split('.').pop().toLowerCase())) {
                        $('#js-preview-video-{{$randId}}').attr('src', fullFilePath);
                        $('#js-preview-video-wrapper-{{$randId}}').show();
                        showDropzone = false;
                    }
                }

                if (showDropzone) {
                    $('#js-dropzone-image-{{$randId}}').show();
                }
            }

            $(document).ready(function() {

                let mediaPickerFileField = document.getElementById('js-media-picker-file-{{$randId}}');
                var imageEditBtn = $('#js-edit-image-{{$randId}}');
                imageEditBtn.click(async function(e) {
                    e.preventDefault();
                    var imData = await mw.top().app.editImageDialog.editImageUrl($('#js-preview-image-{{$randId}}').attr('src'));
                    if(imData) {
                        $('#js-preview-image-{{$randId}}').attr('src', imData.src);
                        mediaPickerFileField.value = imData.src;
                        mediaPickerFileField.dispatchEvent(new Event('input'));
                    }
                });
                if(mediaPickerFileField && mediaPickerFileField.value) {
                    generatePreview{{$randId}}(mediaPickerFileField.value);
                }
                $('.js-remove-file-{{$randId}}').click(function() {

                    mediaPickerFileField.value = '';
                    mediaPickerFileField.dispatchEvent(new Event('input'));

                    generatePreview{{$randId}}(false);

                });

                $('.js-select-file-{{$randId}}').click(function() {
                    var dialog{{$randId}};
                    var picker{{$randId}} = new mw.filePicker({
                        type: '{{$allowedType}}',
                        label: false,
                        autoSelect: false,
                        footer: true,
                        _frameMaxHeight: true,
                        onResult: function (res) {
                            var url = res.src ? res.src : res;
                            if(!url) return;
                            url = url.toString();

                            dialog{{$randId}}.remove();

                            mediaPickerFileField.value = url;
                            mediaPickerFileField.dispatchEvent(new Event('input'));

                            generatePreview{{$randId}}(url);

                        }
                    });
                    dialog{{$randId}} = mw.top().dialog({
                        content: picker{{$randId}}.root,
                        title: mw.lang('Select file'),
                        footer: false,
                        width: 860
                    });
                });
            });
        </script>

    </div>
</div>

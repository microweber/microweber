@props(['label' => '','allowedType'=>'images'])

<div class="form-control-live-edit-label-wrapper">

    @php
        $randId = rand(111,999).time();
    @endphp
    <div>

        <input type="hidden" id="js-media-picker-image-{{$randId}}" {!! $attributes->merge([]) !!} />

        <div id="js-preview-image-wrapper-{{$randId}}" style="display:none">
            <div class="d-flex justify-content-between">
                <div>
                    <img src="" id="js-preview-image-{{$randId}}" />
                </div>
                <div class="d-flex gap-2">
                    <x-microweber-ui::button-action type="button" class="btn-sm js-select-image-{{$randId}}">
                        Change
                    </x-microweber-ui::button-action>
                    <x-microweber-ui::button-action type="button" class="js-edit-image-{{$randId}}" style="display:none" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"> <path d="M22.7 14.3L21.7 15.3L19.7 13.3L20.7 12.3C20.8 12.2 20.9 12.1 21.1 12.1C21.2 12.1 21.4 12.2 21.5 12.3L22.8 13.6C22.9 13.8 22.9 14.1 22.7 14.3M13 19.9V22H15.1L21.2 15.9L19.2 13.9L13 19.9M11.21 15.83L9.25 13.47L6.5 17H13.12L15.66 14.55L13.96 12.29L11.21 15.83M11 19.9V19.05L11.05 19H5V5H19V11.31L21 9.38V5C21 3.9 20.11 3 19 3H5C3.9 3 3 3.9 3 5V19C3 20.11 3.9 21 5 21H11V19.9Z" /></svg>
                    </x-microweber-ui::button-action>

                    <x-microweber-ui::button-action type="button" class="js-remove-image-{{$randId}}">
                        <svg class="text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"></path></svg>
                    </x-microweber-ui::button-action>
                </div>
            </div>
        </div>

        <div id="js-dropzone-image-{{$randId}}" class="dropzone mw-dropzone js-select-image-{{$randId}}">
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
            $(document).ready(function() {

                let mediaPickerImageField = document.getElementById('js-media-picker-image-{{$randId}}');
                var imageEditBtn = $('.js-edit-image-{{$randId}}');


                if (mediaPickerImageField.value !== '') {
                    $('#js-dropzone-image-{{$randId}}').hide();
                    $('#js-preview-image-{{$randId}}').attr('src',  mediaPickerImageField.value);
                    $('#js-preview-image-wrapper-{{$randId}}').show();
                    imageEditBtn.show()
                }

                

         
 

                imageEditBtn.click(async function(e) {
                    e.preventDefault()
                    var src = await mw.top().app.editImageDialog($('#js-preview-image-{{$randId}}').attr('src'));
                    console.log(src)
                    if(src) {
                        $('#js-preview-image-{{$randId}}').attr('src', src);
                        mediaPickerImageField.value = src;
                        mediaPickerImageField.dispatchEvent(new Event('input'));
                    }
                });

                $('.js-select-image-{{$randId}}').click(function() {
                    var dialog;
                    var picker = new mw.filePicker({
                        type: '{{$allowedType}}',
                        label: false,
                        autoSelect: false,
                        footer: true,
                        _frameMaxHeight: true,
                        onResult: function (res) {
                            var url = res.src ? res.src : res;
                            if(!url) return;
                            url = url.toString();

                            $('#js-dropzone-image-{{$randId}}').hide();
                            $('#js-preview-image-{{$randId}}').attr('src', url);
                            $('#js-preview-image-wrapper-{{$randId}}').show();

                            mediaPickerImageField.value = url;
                            mediaPickerImageField.dispatchEvent(new Event('input'));

                            dialog.remove();
                            imageEditBtn.show()
                        }
                    });
                    dialog = mw.top().dialog({
                        content: picker.root,
                        title: mw.lang('Select image'),
                        footer: false,
                        width: 860
                    });
                });
            });
        </script>

    </div>
</div>

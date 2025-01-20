@php
$up = 'up' . uniqid() . rand() . rand() . crc32($data['id']);
$rand = uniqid();
@endphp

<div class="col-md-{{ $settings['field_size'] }}">
    <div class="form-group">
        @if($settings['show_label'])
            <label class="form-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color:red;">*</span>
                @endif
            </label>
        @endif

        <div class="relative mw-custom-field-upload" id="upload_{{ $rand }}">
            <div class="row">
                <div class="col">
                    <div class="custom-file custom-file-{{ $rand }}">
                        <input type="file" 
                            name="{{ $data['name_key'] }}" 
                            class="custom-file-input custom-file-input-{{ $rand }}" 
                            id="customFile{{ $rand }}"
                        >
                        <label class="custom-file-label custom-file-label-{{ $rand }}" for="customFile{{ $rand }}">
                            <i class="mdi mdi-upload"></i> {{ __("Browse") }}
                        </label>
                    </div>
                    <div class="valid-feedback">{{ __("Success! You've done it.") }}</div>
                    <div class="invalid-feedback">{{ __('Error! The value is not valid.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function (){
        $('#customFile{{ $rand }}').change(function(e) {
            var fileName = e.target.files[0].name;
            // change name of actual input that was uploaded
            $(e.target).next().html(fileName);
        });
    });
</script>

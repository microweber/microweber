<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="d-flex flex-column my-2">
        @if($settings['show_label'])
            <label class="form-label my-3">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <div class="d-flex align-items-center">
            <input type="color" 
                class="form-control form-control-color" 
                @if($settings['required']) required @endif
                data-custom-field-id="{{ $data['id'] }}"
                data-custom-field-error-text="{{ $data['error_text'] }}"
                name="{{ $data['name_key'] }}"
                value="{{ $data['value'] }}"
                placeholder="{{ $data['placeholder'] }}"
            />
        </div>

        <div class="valid-feedback">{{ __("Success! You've done it.") }}</div>
        <div class="invalid-feedback">{{ __('Error! The value is not valid.') }}</div>

        @if($data['help'])
            <div class="form-text mt-1">{{ $data['help'] }}</div>
        @endif
    </div>
</div>

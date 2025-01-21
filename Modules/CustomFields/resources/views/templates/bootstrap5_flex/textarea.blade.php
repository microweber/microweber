<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">
        @if($settings['show_label'])
            <label class="form-label me-2 align-self-center mb-0 col-xl-4 col-auto">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <textarea type="text" 
            rows="{{ $settings['rows'] }}" 
            cols="{{ $settings['cols'] }}" 
            class="form-control" 
            @if($settings['required']) required @endif 
            data-custom-field-id="{{ $data['id'] }}" 
            data-custom-field-error-text="{{ $data['error_text'] }}" 
            name="{{ $data['name_key'] }}" 
            placeholder="{{ $data['placeholder'] }}"
        >{{ $data['value'] }}</textarea>
        <div class="valid-feedback">{{ __("Success! You've done it.") }}</div>
        <div class="invalid-feedback">{{ __('Error! The value is not valid.') }}</div>

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

@php
$rand = uniqid();
@endphp

<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">
        @if($settings['show_label'])
            <label class="form-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <input type="url"
            class="form-control"
            id="custom_field_help_text{{ $rand }}"
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            data-custom-field-error-text="{{ $data['error_text'] }}"
            name="{{ $data['name_key'] }}"
            value="{{ $data['value'] }}"
            placeholder="{{ $data['placeholder'] }}"
        />
        <div class="valid-feedback">{{ __("Success! You've done it.") }}</div>
        <div class="invalid-feedback">{{ __('Error! The value is not valid.') }}</div>

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

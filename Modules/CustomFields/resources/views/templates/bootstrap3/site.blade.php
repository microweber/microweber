@php
    $rand = uniqid();
@endphp

<div class="col-md-{{ $settings['field_size'] }}">
    <div class="form-group">
        @if($settings['show_label'])
            <label class="form-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        @if($data['help'])
            <small class="mw-custom-field-help">{{ $data['help'] }}</small>
        @endif

        <input type="url" 
            class="form-select form-control" 
            id="custom_field_help_text{{ $rand }}" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            data-custom-field-error-text="{{ $data['error_text'] }}"
            value="{{ $data['value'] }}"
            name="{{ $data['name_key'] }}"
            placeholder="{{ $data['placeholder'] }}"/>
    </div>
</div>

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

        <input type="text" 
            class="form-control" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            data-custom-field-error-text="{{ $data['error_text'] }}"
            name="{{ $data['name_key'] }}"
            value="{{ $data['value'] }}"
            placeholder="{{ $data['placeholder'] }}"/>

        @if($data['help'])
            <small class="mw-custom-field-help">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

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

        <textarea type="text" 
            rows="{{ $settings['rows'] }}" 
            cols="{{ $settings['cols'] }}" 
            class="form-control" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            data-custom-field-error-text="{{ $data['error_text'] }}"
            name="{{ $data['name_key'] }}"
            placeholder="{{ $data['placeholder'] }}">{{ $data['value'] }}</textarea>

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif
    </div>
</div>

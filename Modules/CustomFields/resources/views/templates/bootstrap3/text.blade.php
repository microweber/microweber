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

        @if($settings['as_text_area'])
            <textarea type="text" 
                class="form-control" 
                rows="{{ $settings['rows'] }}" 
                cols="{{ $settings['cols'] }}" 
                @if($settings['required']) required @endif
                data-custom-field-id="{{ $data['id'] }}" 
                data-custom-field-error-text="{{ $data['error_text'] }}" 
                name="{{ $data['name_key'] }}" 
                value="{{ $data['value'] }}" 
                placeholder="{{ $data['placeholder'] }}">{{ $data['value'] }}</textarea>
        @else
            <input type="text" 
                class="form-control" 
                @if($settings['required']) required @endif
                data-custom-field-id="{{ $data['id'] }}" 
                data-custom-field-error-text="{{ $data['error_text'] }}" 
                name="{{ $data['name_key'] }}" 
                value="{{ $data['value'] }}" 
                placeholder="{{ $data['placeholder'] }}"/>
        @endif

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif
    </div>
</div>

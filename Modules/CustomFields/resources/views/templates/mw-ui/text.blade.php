<div class="mw-flex-col-md-{{ $settings['field_size'] }}">
    <div class="mw-ui-field-holder">
        @if($settings['show_label'])
            <label class="mw-ui-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        @if($data['help'])
            <small class="mw-custom-field-help">{{ $data['help'] }}</small>
        @endif

        <div class="mw-ui-controls">
            @if($settings['as_text_area'])
                <textarea type="text" 
                    class="mw-ui-field" 
                    @if($settings['required']) required @endif
                    data-custom-field-id="{{ $data['id'] }}" 
                    data-custom-field-error-text="{{ $data['error_text'] }}" 
                    name="{{ $data['name_key'] }}" 
                    placeholder="{{ $data['placeholder'] }}">{{ $data['value'] }}</textarea>
            @else
                <input type="text" 
                    class="mw-ui-field" 
                    @if($settings['required']) required @endif
                    data-custom-field-id="{{ $data['id'] }}" 
                    data-custom-field-error-text="{{ $data['error_text'] }}" 
                    name="{{ $data['name_key'] }}" 
                    value="{{ $data['value'] }}" 
                    placeholder="{{ $data['placeholder'] }}"/>
            @endif
        </div>
    </div>
</div>

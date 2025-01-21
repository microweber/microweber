<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="form-group">
        @if($settings['show_label'])
            <label class="control-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <input type="color" 
            class="form-control" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            data-custom-field-error-text="{{ $data['error_text'] }}"
            name="{{ $data['name_key'] }}"
            value="{{ $data['value'] }}"
            placeholder="{{ $data['placeholder'] }}"
        />

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif
    </div>
</div>

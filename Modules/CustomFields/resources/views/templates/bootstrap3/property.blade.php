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

        <input type="hidden" 
            class="form-control" 
            data-custom-field-id="{{ $data['id'] }}" 
            name="{{ $data['name_key'] }}" 
            value="{{ $data['value'] }}"/>

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif

        <div class="controls">
            {{ $data['value'] }}
        </div>
    </div>
</div>

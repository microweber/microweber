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

        @foreach($data['values'] as $key => $value)
            <label class="mw-ui-check">
                <input type="radio" 
                    @if($settings['required'] && $loop->first) required @endif
                    data-custom-field-id="{{ $data['id'] }}" 
                    value="{{ $value }}" 
                    name="{{ $data['name_key'] }}"
                    @if($data['value'] && $data['value'] == $value) checked="checked" @endif />
                <span></span>
                <span>{{ $value }}</span>
            </label>
        @endforeach
    </div>
</div>

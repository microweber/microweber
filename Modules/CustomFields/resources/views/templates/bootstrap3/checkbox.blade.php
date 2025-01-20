<div class="col-md-{{ $settings['field_size'] }}">
    <div class="form-group">
        @if($settings['show_label'])
            <label class="form-label">{{ $data['name'] }}</label>
        @endif

        <div class="mw-customfields-checkboxes">
            @foreach($data['values'] as $value)
                <div class="checkbox">
                    <label>
                        <input type="checkbox" 
                            name="{{ $data['name_key'] }}[]" 
                            id="field-{{ $data['id'] }}" 
                            data-custom-field-id="{{ $data['id'] }}" 
                            value="{{ $value }}"/> {{ $value }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

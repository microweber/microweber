<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="form-group">
        @if($settings['show_label'])
            <label class="form-label my-3">{{ $data['name'] }}</label>
        @endif

        @foreach($data['values'] as $key => $value)
            <div class="custom-control custom-checkbox my-2">
                <input class="form-check-input" 
                    type="checkbox" 
                    name="{{ $data['name_key'] }}[]" 
                    id="field-{{ $loop->iteration }}-{{ $data['id'] }}" 
                    data-custom-field-id="{{ $data['id'] }}" 
                    value="{{ $value }}"/>
                <label class="custom-control-label" 
                    for="field-{{ $loop->iteration }}-{{ $data['id'] }}">{{ $value }}</label>
            </div>
        @endforeach
    </div>
</div>

<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mw-text-start my-2">
        @if($settings['show_label'])
            <label class="form-label my-3">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        @foreach($data['values'] as $key => $value)
            <div class="custom-control custom-radio my-2">
                <input type="radio" 
                    id="custom-radio-{{ $data['id'] }}-{{ $key }}" 
                    class="form-check-input" 
                    @if($settings['required'] && $loop->first) required @endif
                    data-custom-field-id="{{ $data['id'] }}" 
                    value="{{ $value }}"
                    name="{{ $data['name_key'] }}">
                <label class="custom-control-label ms-2" 
                    for="custom-radio-{{ $data['id'] }}-{{ $key }}">
                    {{ $value }}
                    @if(isset($data['values_price_modifiers']) && !empty($data['values_price_modifiers']) && isset($data['values_price_modifiers'][$key]) && $data['values_price_modifiers'][$key])
                        (+{{ currency_format($data['values_price_modifiers'][$key]) }})
                    @endif
                </label>
            </div>
        @endforeach

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

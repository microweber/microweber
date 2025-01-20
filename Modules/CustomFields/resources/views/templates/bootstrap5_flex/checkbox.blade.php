<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">

        @if($settings['show_label'])
            <label class="form-label my-3">{{ $data['name'] }}</label>
        @endif

        @foreach($data['values'] as $key => $value)
            <div class="custom-control custom-checkbox my-2">
                <input class="form-check-input" type="checkbox" name="{{ $data['name_key'] }}[]" id="field-{{ $loop->iteration }}-{{ $data['id'] }}" data-custom-field-id="{{ $data['id'] }}" value="{{ $value }}"/>
                <label class="custom-control-label" for="field-{{ $loop->iteration }}-{{ $data['id'] }}">{{ $value }}

                    @if(isset($data['values_price_modifiers']) && !empty($data['values_price_modifiers']) && isset($data['values_price_modifiers'][$key]) && $data['values_price_modifiers'][$key])
                        (+{{ currency_format($data['values_price_modifiers'][$key]) }})
                    @endif

                </label>
            </div>
        @endforeach
    </div>
</div>

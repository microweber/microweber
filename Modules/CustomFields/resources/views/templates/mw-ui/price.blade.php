@php
    $rand = rand();
@endphp

<div class="mw-flex-col-md-{{ $settings['field_size'] }}">
    @if($settings['make_select'])
        <option type="url" 
            class="form-select {{ $settings['class'] }}" 
            id="custom_field_help_text{{ $rand }}" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}" 
            name="{{ $data['name_key'] }}" 
            value="{{ $data['value'] }}">
        </option>
    @else
        <div class="mw-custom-field-group mw-custom-field-price">
            <label class="mw-custom-field-label">{{ $data['name'] }}</label>
            <div class="mw-custom-field-form-controls">
                {{ $data['value'] }}
                <input type="hidden" 
                    @if($settings['required']) required @endif
                    data-custom-field-id="{{ $data['id'] }}"
                    name="{{ $data['name_key'] }}"
                    id="custom_field_help_text{{ $rand }}"
                    value="{{ $data['value'] }}">

                @if($data['options']['old_price'])
                    <span style="text-decoration: line-through">{{ $data['options']['old_price'][0] }}</span>
                @endif
            </div>
        </div>
    @endif
</div>

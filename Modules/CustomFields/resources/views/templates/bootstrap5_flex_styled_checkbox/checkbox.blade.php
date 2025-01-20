<style>
    .form-selectgroup-item {
        padding: 30px;
        height: 100%;
        display: grid;
        align-items: center;
        justify-content: center;

        border-radius: var(--mw-form-control-border-radius);
        border-color: var(--mw-form-control-border-color);
        border-width: var(--mw-form-control-border-size);
        border-style: var(--mw-form-control-border-style);
        background-color: var(--mw-form-control-background);
        padding-block: var(--mw-form-control-padding-block);
        padding-inline: var(--mw-form-control-padding-inline);
        color: var(--mw-form-control-text-color);
    }

    .form-selectgroup-item:has(input:checked) {
        border-color: var(--mw-primary-color);
        background-color: transparent;
    }

    .form-selectgroup-item:hover {
        border-color: var(--mw-primary-color);
        background-color: transparent;
    }
</style>

<div class="mb-3 row col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    @if($settings['show_label'])
        <label class="form-label my-3">{{ $data['name'] }}</label>
    @endif

    @foreach($data['values'] as $key => $value)
        @php $i = $loop->iteration; @endphp
        <div class="col-lg-3 col-6 form-selectgroup form-selectgroup-pills my-3">
            <label class="form-selectgroup-item" for="field-{{ $i }}-{{ $data['id'] }}">
                <input class="form-selectgroup-input mb-3" 
                    type="checkbox" 
                    name="{{ $data['name_key'] }}[]" 
                    id="field-{{ $i }}-{{ $data['id'] }}" 
                    data-custom-field-id="{{ $data['id'] }}" 
                    value="{{ $value }}"
                />
                <span class="form-selectgroup-label">{{ $value }}</span>

                @if(isset($data['values_price_modifiers']) && !empty($data['values_price_modifiers']) && isset($data['values_price_modifiers'][$key]) && $data['values_price_modifiers'][$key])
                    (+{{ currency_format($data['values_price_modifiers'][$key]) }})
                @endif
            </label>
        </div>
    @endforeach
</div>

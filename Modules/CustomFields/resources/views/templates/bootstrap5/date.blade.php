@php
    $rand = uniqid();
@endphp

<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mw-text-start my-2">
        @if($settings['show_label'])
            <label class="form-label my-3">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color:red;">*</span>
                @endif
            </label>
        @endif

        <input type="date" 
            class="form-control js-bootstrap5-datepicker"
            @if($settings['required']) required @endif
            data-date-format="{{ $settings['date_format'] }}"
            data-custom-field-id="{{ $data['id'] }}"
            name="{{ $data['name_key'] }}"
            value="{{ $data['value'] }}"
            id="date_{{ $rand }}"
            placeholder="{{ $data['placeholder'] }}"
            autocomplete="off"/>

        <div class="valid-feedback">{{ __("Success! You've done it.") }}</div>
        <div class="invalid-feedback">{{ __('Error! The value is not valid.') }}</div>

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

<script>
    mw.lib.require("bootstrap_datepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        if($('#date_{{ $rand }}') && $('#date_{{ $rand }}').datepicker) {
            $('#date_{{ $rand }}').datepicker({
                dateFormat: '{{ $settings['date_format'] }}',
                language: "{{ current_lang_abbr() }}"
            });
        }
    });
</script>

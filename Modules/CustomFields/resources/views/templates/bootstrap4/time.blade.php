<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="form-group">
        @if($settings['show_label'])
            <label class="form-label my-3">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <input type="text" 
            class="form-control js-bootstrap4-timepicker" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            name="{{ $data['name_key'] }}"
            value="{{ $data['value'] }}"
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
    mw.lib.require("bootstrap_datetimepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.js-bootstrap4-timepicker').datetimepicker({
            pickDate: false,
            minuteStep: 15,
            pickerPosition: 'bottom-right',
            format: 'HH:ii p',
            autoclose: true,
            showMeridian: true,
            startView: 1,
            maxView: 1
        });
    });
</script>

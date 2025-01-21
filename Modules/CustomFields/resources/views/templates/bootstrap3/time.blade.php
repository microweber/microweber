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

        <div id="datetimepicker3">
            <input type="text" 
                class="form-control js-bootstrap3-timepicker" 
                @if($settings['required']) required @endif
                data-custom-field-id="{{ $data['id'] }}"
                name="{{ $data['name_key'] }}"
                value="{{ $data['value'] }}"
                placeholder="{{ $data['placeholder'] }}"
                autocomplete="off"/>
        </div>

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif
    </div>
</div>

<script>
    mw.lib.require("bootstrap_datetimepicker");
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.js-bootstrap3-timepicker').datetimepicker({
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

@if($settings['multiple'])
    <script type="text/javascript">
        mw.lib.require('chosen');
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-mw-select-{{ $data['id'] }}").chosen({width: '100%'});
        });
    </script>
@endif

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

        <select class="form-control js-mw-select-{{ $data['id'] }}"
            @if($settings['multiple']) multiple="multiple" @endif
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}"
            name="{{ $data['name_key'] }}">

            @if(!empty($data['placeholder']))
                <option disabled selected value>{{ $data['placeholder'] }}</option>
            @endif

            @foreach($data['values'] as $key => $value)
                <option data-custom-field-id="{{ $data['id'] }}" value="{{ $value }}">
                    {{ $value }}
                </option>
            @endforeach
        </select>

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

<script>mw.require('forms.js');</script>

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

        <input type="number" 
            onKeyup="mw.form.typeNumber(this);" 
            class="form-control" 
            @if($settings['required']) required @endif
            data-custom-field-id="{{ $data['id'] }}" 
            value="{{ $data['value'] }}" 
            name="{{ $data['name_key'] }}" 
            placeholder="{{ $data['placeholder'] }}" />

        @if($data['help'])
            <span class="help-block">{{ $data['help'] }}</span>
        @endif
    </div>
</div>

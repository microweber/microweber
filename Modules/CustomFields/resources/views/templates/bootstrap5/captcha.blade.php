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

        <div class="mw-custom-field-form-controls">
            <module type="captcha"/>
        </div>
    </div>
</div>

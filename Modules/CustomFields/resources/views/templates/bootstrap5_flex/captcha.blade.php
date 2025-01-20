<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">
        @if($settings['show_label'])
            <label class="form-label me-2 align-self-center mb-0 col-xl-4 col-auto">
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

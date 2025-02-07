<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">
        @if($settings['show_label'])
            <label class="form-label">
                {{ $data['name'] }}
                @if($settings['required'])
                    <span style="color: red;">*</span>
                @endif
            </label>
        @endif

        <input type="hidden"
            class="form-control"
            data-custom-field-id="{{ $data['id'] }}"
            name="{{ $data['name_key'] }}"
            value="{{ $data['value'] }}"
        />

        <div class="controls">
            {{ $data['value'] }}
        </div>

        @if($data['help'])
            <small class="form-text text-muted">{{ $data['help'] }}</small>
        @endif
    </div>
</div>

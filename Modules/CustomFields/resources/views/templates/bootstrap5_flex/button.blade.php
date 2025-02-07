<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mb-3 d-flex gap-3 flex-wrap">
        <label class="form-label">&nbsp;</label>
        <input type="{{ $settings['type'] }}"
            class="mw-ui-btn btn-default"
            value="{{ __($data['name']) }}"
        />
    </div>
</div>

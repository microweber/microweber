<div class="col-sm-{{ $settings['field_size_mobile'] }} col-md-{{ $settings['field_size_tablet'] }} col-lg-{{ $settings['field_size_desktop'] }}">
    <div class="mw-text-start my-2">
        <label class="form-label my-3">&nbsp;</label>
        <input type="{{ $settings['type'] }}" 
            class="mw-ui-btn btn btn-default" 
            value="{{ __($data['name']) }}" />
    </div>
</div>

<div class="row my-2">
    <div class="col-md-9 col-12">
        <div class="form-group">
            @if(get_option('disable_captcha', $params['id']) != 'y')
                <label class="custom-field-title">@lang('Enter Security code')</label>
                <div class="row captcha-holder" style="width: 262px;">
                    <div class="col">
                        <module type="captcha" id="captcha_contact_form-{{ $form_id }}"/>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-3 col-12">
        <label>&nbsp;</label>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" :disabled="$data.loading">
                <span x-show="!$data.loading">{{ $button_text }}</span>
                <span x-cloak x-show="$data.loading">@lang('Sending...')</span>
            </button>
        </div>
    </div>
</div>

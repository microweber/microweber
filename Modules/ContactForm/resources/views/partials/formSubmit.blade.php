<div class="row">
    <div class="form-group my-2">
        @if(get_option('disable_captcha', $params['id']) != 'y')
            <label class="custom-field-title">@lang('Enter Security code')</label>
            <module type="captcha" id="captcha_contact_form-{{ $form_id }}"/>

        @endif
    </div>

    <div class="form-group my-2">
        <button type="submit" class="btn btn-primary w-100" :disabled="$data.loading">
            <span x-show="!$data.loading">{{ $button_text }}</span>
            <span x-cloak x-show="$data.loading">@lang('Sending...')</span>
        </button>
    </div>
</div>

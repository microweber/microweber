{{--
type: layout
name: Default
description: Default comments template
--}}

@once
    <script src="{{ asset('modules/captcha/js/captcha-alpine.js') }}" data-mw-captcha-alpine-inline></script>
@endonce

<div
    wire:ignore
    x-data="captchaAlpine"
    @callback.window="<?php isset($params['data-callback']) ? print $params['data-callback'] . '($event.detail)' : '' ?>"
>


    <div class="d-flex align-items-center justify-content-end ms-auto">
        <div class="col-auto">
                 <img
                    x-on:click="refreshCaptcha($el)"
                    class="mw-captcha-img"
                    id="captcha-{{ $form_id }}"
                    src="{{ api_link('captcha') }}?w=100&h=60&uid={{ uniqid($form_id) }}&rand={{ rand(1, 10000) }}&id={{ $params['id'] ?? $form_id }}"
                    alt="Captcha"
                    style="cursor: pointer;"
                />
         </div>
        <div class="col-5">
            <input
                x-model="captchaValue"
                name="captcha"
                id="captcha-{{ $params['id'] ?? $form_id }}-input"
                type="text"
                required
                class="mw-captcha-input form-control"
                placeholder="@lang('Security code')"
                autocomplete="off"
            />
        </div>
    </div>
</div>

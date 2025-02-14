{{--
type: layout
name: Default
description: Default comments template
--}}

<div
    wire:ignore
    x-ignore
    ax-load
    x-load-src="{{ asset('modules/captcha/js/captcha-alpine.js') }}"
    x-data="captchaAlpine"
    x-load="visible"
    @callback.window="<?php isset($params['data-callback']) ? print $params['data-callback'] . '($event.detail)' : '' ?>"
>
    <div class="d-flex align-items-center justify-content-end ms-auto" wire:ignore>
        <div class="col-auto">
            <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                <img
                    @click="refreshCaptcha($el)"
                    class="mw-captcha-img"
                    id="captcha-{{ $form_id }}"
                    src="{{ api_link('captcha') }}?w=100&h=60&uid={{ uniqid($form_id) }}&rand={{ rand(1, 10000) }}&id={{ $params['id'] }}"
                    onerror="refreshCaptcha(this)"
                />
            </a>
        </div>
        <div class="col-5">
            <input
                x-model="captchaValue"
                name="captcha"
                id="captcha-{{ $params['id'] }}-input"
                type="text"
                required
                class="mw-captcha-input form-control"
                placeholder="@lang('Security code')"
            />
        </div>
    </div>
</div>

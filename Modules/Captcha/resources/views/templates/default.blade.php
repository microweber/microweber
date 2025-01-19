{{--
type: layout
name: Default
description: Default comments template
--}}

<div
    wire:ignore
    x-ignore
    ax-load

    ax-load-src="{{ asset('modules/captcha/js/captcha-alpine.js') }}"

    x-data="captchaAlpine"
    x-load="visible"
    @callback.window="<?php isset($params['data-callback']) ? print $params['data-callback'] . '($event.detail)' : '' ?>"
>
    <div class="mw-ui-row"  >
        <div class="mw-captcha" style="max-width: 400px; margin: 15px;">
            <div class="form-group" wire:ignore>
                <div class="captcha-holder">
                    <div class="mw-ui-col" style="width: 100px;">
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
                    <div class="mw-ui-col">
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
        </div>
    </div>
</div>

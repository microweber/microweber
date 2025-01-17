@php
/*
type: layout
name: Dark
description: Dark theme for your website
icon: dark.png
version: 0.2
*/
@endphp


<div class="contact-form-container contact-form-template-dark">
    <div class="contact-form">
        <div class="edit" field="contact_form_title" rel="newsletter_module" data-id="{{ $params['id'] }}">
            <h3 class="element contact-form-title">@lang('Leave a Message')</h3>
        </div>
        <form class="mw_form" data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post">

            <module type="custom_fields" for-id="{{ $params['id'] }}" data-for="module" default-fields="{{ $default_fields }}"/>

            @if(get_option('disable_captcha', $params['id']) != 'y')
                <div class="control-group form-group">
                    <label>@lang('Security code')</label>
                    <div class="mw-ui-row captcha-holder">
                        <div class="mw-ui-col">
                            <module type="captcha"/>
                        </div>
                    </div>
                </div>
            @endif

            <module type="btn" button_action="submit" button_style="btn btn-default" button_text="{{ $button_text }}"/>

        </form>
    </div>
    <div class="message-sent" id="msg{{ $form_id }}">
        <span class="message-sent-icon message-sent-icon-orange"></span>
        <p>@lang('Your Email was sent successfully')</p>
    </div>
</div>

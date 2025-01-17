@php
    /*
    type: layout
    name: Default
    description: Default template for Contact form
    icon: dream.png
    */
@endphp

<div class="contact-form-container contact-form-template-dream"
     x-load="visible"
     x-load-src="{{ asset('modules/contact_form/js/contact-form-alpine.js') }}"
     x-data="contactForm('{{ $params['id'] }}')"
>
    <div class="contact-form">
        <div class="edit" field="contact_form_title" rel="contact_form_module" data-id="{{ $params['id'] }}">
            <h3 class="element contact-form-title">@lang('Leave a Message')</h3>
        </div>
        <form class="mw_form" data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post"
              x-on:submit="submitForm">

            <module type="custom_fields" for-id="{{ $params['id'] }}" data-for="module"
                    default-fields="{{ $default_fields }}"/>

            @if(isset($show_newsletter_subscription) && $show_newsletter_subscription == 'y' && !$newsletter_subscribed)
                <div class="mw-flex-row">
                    <div class="mw-flex-col-md-12 mw-flex-col-sm-12 mw-flex-col-xs-12">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox my-2">
                                <label class="mw-ui-check" style="padding-top:0">
                                    <input type="checkbox" name="newsletter_subscribe" value="1" autocomplete="off"/>
                                    <span></span>
                                    <span>@lang('Please email me your monthly news and special offers')</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mw-flex-row">
                <div class="mw-flex-col-md-9 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <div class="control-group form-group">
                        @if(get_option('disable_captcha', $params['id']) != 'y')
                            <label class="custom-field-title">@lang('Enter Security code')</label>
                            <div class="mw-ui-row captcha-holder" style="width: 262px;">
                                <div class="mw-ui-col">
                                    <module type="captcha" id="captcha_contact_form-{{ $form_id }}"/>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mw-flex-col-md-3 mw-flex-col-sm-12 mw-flex-col-xs-12">
                    <label>&nbsp;</label>
                    <div class="control-group form-group">
                        <button type="submit" class="btn btn-primary" :disabled="$data.loading">
                            <span x-show="!$data.loading">{{ $button_text }}</span>
                            <span x-cloak x-show="$data.loading">@lang('Sending...')</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="message-sent" id="msg{{ $form_id }}" x-show="success" x-cloak>
        <span class="message-sent-icon"></span>
        <p>@lang('Your Email was sent successfully')</p>
    </div>
</div>

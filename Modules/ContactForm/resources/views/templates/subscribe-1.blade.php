@php
    /*
    type: layout
    name: Subscribe 1
    description: Subscribe 1
    */
@endphp
<link href="{{ asset('modules/contact_form/css/app.css') }}" rel="stylesheet" type="text/css"/>

<form class="contact-form-container mw_form" data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post">
    <div class="mw-message-form-wrapper message-sent" id="msg{{ $form_id }}" style="display: none;">
        <span class="message-sent-icon"></span>
        <p class="text-success">@lang('Your Email was sent successfully')</p>
    </div>

    <div class="form-inline justify-content-center">
        <div class="form-group d-flex">
            <module type="custom_fields" for-id="{{ $params['id'] }}" data-for="module"
                    default-fields="Email[type=email,field_size=12,show_placeholder=true]" input_class="form-control-lg"/>
            <br><br>
            <module type="btn" button_action="submit" class="ms-2" button_style="btn-primary mb-3" button_text="@lang('Subscribe')"/>
        </div>
    </div>

    @if(isset($show_newsletter_subscription) && $show_newsletter_subscription == 'y' && !$newsletter_subscribed)
        <div class="form-group">
            <div class="custom-control custom-checkbox my-2">
                <label class="mw-ui-check" style="padding-top:0">
                    <input type="checkbox" name="newsletter_subscribe" value="1" autocomplete="off"/> <span></span>
                    <span>@lang('Please email me your monthly news and special offers')</span>
                </label>
            </div>
        </div>
    @endif

    @if(isset($require_terms) && $require_terms && isset($require_terms_when) && $require_terms_when == 'b')
        <module type="users/terms" data-for="contact_form_default"/>
    @endif
</form>

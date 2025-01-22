@php
    /*
    type: layout
    name: Skin-6
    description: Skin-6
    */
@endphp
<link href="{{ asset('modules/contact_form/css/app.css') }}" rel="stylesheet" type="text/css"/>

<style>
    input:hover, label:hover, input:focus, label:focus, textarea:focus, textarea:hover, select:hover, select:focus {
        border-color: var(--mw-primary-color) !important;
        background-color: transparent !important;
    }
</style>

<form class="contact-form-container mw_form mw-form-6 ps-3" data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post">
    <div class="mw-message-form-wrapper message-sent" id="msg{{ $form_id }}" style="display: none;">
        <span class="message-sent-icon"></span>
        <p class="text-success">@lang('Your Email was sent successfully')</p>
    </div>

    <module type="custom_fields" template="bootstrap5_flex_styled_checkbox" for-id="{{ $params['id'] }}" data-for="module"
            default-fields="Name[type=text,field_size=6,show_placeholder=true],Email[type=email,field_size=6,show_placeholder=true],Message[type=textarea,field_size=12,show_placeholder=true]" input_class="form-control"/>

    <br><br>
    <module type="btn" button_action="submit" button_style="btn-primary d-flex ms-auto" button_text="Send"/>

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

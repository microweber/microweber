@php
    /*
    type: layout
    name: Ziza CTA Form
    description: Ziza CTA Form
    */
@endphp
<link href="{{ asset('modules/contact_form/css/app.css') }}" rel="stylesheet" type="text/css"/>

<form class="contact-form-container col-xl-10 mw_form d-flex flex-wrap align-items-center justify-content-end ms-auto ziza-cta-div-form"
      data-form-id="{{ $form_id }}"
      name="{{ $form_id }}"
      method="post">

    <div class="mw-message-form-wrapper message-sent" id="msg{{ $form_id }}" style="display: none;">
        <span class="message-sent-icon"></span>
        <p class="text-success">@lang('Your Email was sent successfully')</p>
    </div>

    <div class="col-md-8 col-12 my-md-0 my-2">
        <module type="custom_fields" for-id="{{ $params['id'] }}" data-for="module" template="bootstrap5_flex"
                default-fields="[type=email,field_size=12,show_placeholder=true]" input_class=""/>
    </div>

    <div class="col-md-4 col-12 my-md-0 my-2">
        <module type="btn" button_action="submit" button_style="btn-primary" button_size="w-100 justify-content-center" button_text="Contact Now"/>
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

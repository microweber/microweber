@php
    /*
    type: layout
    name: Skin-2
    description: Skin-2
    */
@endphp
<link href="{{ asset('modules/contact_form/css/app.css') }}" rel="stylesheet" type="text/css"/>

<form class="contact-form-container mw_form" data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post">
    <div class="mw-message-form-wrapper message-sent" id="msg{{ $form_id }}" style="display: none">
        <span class="message-sent-icon"></span>
        <p class="text-success">@lang('Your Email was sent successfully')</p>
    </div>

    <module type="custom_fields" template="bootstrap5" for-id="{{ $params['id'] }}" data-for="module"
            default-fields="Email[type=email,field_size=12,show_placeholder=true], Name[type=text,field_size=12,show_placeholder=true]" input_class="form-control"/>

    @if(isset($require_terms) && $require_terms && isset($require_terms_when) && $require_terms_when == 'b')
        <module type="users/terms" data-for="contact_form_default"/>
    @endif

    <br><br>
    <module type="btn" button_action="submit" button_style="btn-primary" button_size="btn-block w-100 justify-content-center" button_text="Send"/>
</form>

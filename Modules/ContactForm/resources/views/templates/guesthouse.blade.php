@php
    /*
    type: layout
    name: Guesthouse
    description: Guesthouse
    */
@endphp
<link href="{{ asset('modules/contact_form/css/app.css') }}" rel="stylesheet" type="text/css"/>

<div
    x-load="visible"
    x-load-src="{{ asset('modules/contact_form/js/contact-form-alpine.js') }}"
    x-data="contactForm('{{ $params['id'] }}')">
    <div class="contact-form-container section-42">
        <form id="contactform" data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post"
              x-on:submit="submitForm">
            @csrf

            <div class="row">
                <div class="col-12">
                    <module type="custom_fields" default-fields="Full Name[type=text,field_size=12,show_placeholder=true], Email[type=email,field_size=12,show_placeholder=true], Phone Number[type=phone,field_size=12,show_placeholder=true], How many nights?[type=text,field_size=6,show_placeholder=true], How many guests?[type=text,field_size=6,show_placeholder=true], From[type=date,field_size=6,show_placeholder=true], To[type=date,field_size=6,show_placeholder=true], Message[type=textarea,field_size=12,show_placeholder=true]" input_class="form-control"/>
                </div>
            </div>

            <div class="module-custom-fields">
                <div class="row">
                    <div class="col-12">
                        <br><br>
                        @include('modules.contact_form::partials.formSubmit')
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('modules.contact_form::partials.successMessage')
</div>

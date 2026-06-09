@php
    /*
    type: layout
    name: Skin-2
    description: Skin-2
    */
@endphp

<div


    x-data="contactForm('{{ $params['id'] }}')">
    <div class="contact-form-container">
        <form data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post"
              x-on:submit="submitForm">

            <module type="custom_fields" template="bootstrap5" for-id="{{ $params['id'] }}" data-for="module"
                    default-fields="Email[type=email,field_size=12,show_placeholder=true], Name[type=text,field_size=12,show_placeholder=true]" input_class="form-control"/>

            @if(isset($require_terms) && $require_terms && isset($require_terms_when) && $require_terms_when == 'b')
                <module type="users/terms" data-for="contact_form_default"/>
            @endif


            @include('modules.contact_form::partials.formSubmit')
        </form>
        @include('modules.contact_form::partials.successMessage')
        @include('modules.contact_form::partials.errorMessage')
    </div>
</div>

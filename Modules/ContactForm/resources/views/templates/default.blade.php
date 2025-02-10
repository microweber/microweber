@php
    /*
    type: layout
    name: Default
    description: Default template for Contact form
    icon: dream.png
    */
@endphp

<link href="{{ asset('modules/contact_form/css/app.css') }}" rel="stylesheet" type="text/css"/>

<div class="contact-form-container contact-form-template-dream"
     x-load="visible"
     x-load-src="{{ asset('modules/contact_form/js/contact-form-alpine.js') }}"
     x-data="contactForm('{{ $params['id'] }}')"
>
    <div class="contact-form">
        <div class="edit" field="contact_form_title" rel="contact_form_module" data-id="{{ $params['id'] }}">
            <h3 class="element contact-form-title">@lang('Leave a Message')</h3>
        </div>
        <form data-form-id="{{ $form_id }}" name="{{ $form_id }}" method="post"
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
            @include('modules.contact_form::partials.formSubmit')
        </form>
    </div>
    @include('modules.contact_form::partials.successMessage')
</div>

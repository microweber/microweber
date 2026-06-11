{{--
type: layout

name: Contacts 1

position: 1

categories: Contact Us
--}}

<style>
    #{{ $params['id'] ?? '' }} .module-google-maps .relative{
        height: 100% !important;
    }

    #{{ $params['id'] ?? '' }} .mw-googlemaps iframe {
        height: 100vh !important;
    }
</style>

<div class="position-relative overflow-hidden">
    <module type="google_maps" class="position-lg-absolute w-100 h-100" style="z-index: 1"/>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-1"
    :has-background="false"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="col-12 col-lg-4 col-md-6 ms-auto p-3 cloneable element background-color-element allow-select regular-mode" style="background-color: #fff;">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">We are here to help and answer any question you might have.</p>
                 <module type="contact_form" template="skin-3"/>
            </x-row>
</x-layout-section>

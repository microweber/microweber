{{--
type: layout

name: Contacts 2

position: 2

categories: Contact Us
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-contacts-skin-2"
    default-padding-top="pt-9"
    default-padding-bottom="pb-9"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="col-12 col-lg-8 mx-auto position-relative p-3 cloneable element background-color-element regular-mode" style="background-color: #fff;">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">We're here to help and answer any question you might have.</p>
                <module type="contact_form" template="skin-3"/>
            </x-row>
</x-layout-section>

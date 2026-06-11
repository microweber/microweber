{{--
type: layout

name: Contacts 17 - Parallax

position: 17

categories: Contact Us
--}}

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-parallax mw-layout-dark-background contacts-guesthouse inverse"
    default-padding-top="mw-p-t-50"
    default-padding-bottom="mw-p-b-90"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/trees.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />
    <div class="mw-layout-container no-element container edit safe-mode safe-mode" field="layout-contacts-skin-17-{{ $params['id'] ?? '' }}" rel="module">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="fx-deactivate mb-3 regular-mode">Request a Reservation</h2>

                <module type="contact_form" template="guesthouse"/>
            </div>
</x-layout-section>

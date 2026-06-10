@php
/*

type: layout

name: Contacts 17 - Parallax

position: 17

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'mw-p-t-50';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'mw-p-b-90';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section mw-layout-parallax mw-layout-dark-background contacts-guesthouse inverse">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/trees.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />
        <div class="mw-layout-container no-element container edit safe-mode safe-mode" field="layout-contacts-skin-17-{{ $params['id'] ?? '' }}" rel="module">
            <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="fx-deactivate mb-3 regular-mode">Request a Reservation</h2>

            <module type="contact_form" template="guesthouse"/>
        </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>

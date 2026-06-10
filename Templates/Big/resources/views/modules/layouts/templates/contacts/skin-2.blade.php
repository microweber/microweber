@php
/*

type: layout

name: Contacts 2

position: 2

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'pt-9';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-9';
}

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section" data-background-position="center center" data-bg-contain="true">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

    <div class="mw-layout-container no-element container edit safe-mode" field="layout-contacts-skin-2-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row col-12 col-lg-8 mx-auto position-relative p-3 cloneable element background-color-element regular-mode" style="background-color: #fff;">
            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
            <p data-mwplaceholder="{{ _e('Enter text here') }}">We're here to help and answer any question you might have.</p>
            <module type="contact_form" template="skin-3"/>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>

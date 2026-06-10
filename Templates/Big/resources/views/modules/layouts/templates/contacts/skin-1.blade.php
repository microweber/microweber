@php
/*

type: layout

name: Contacts 1

position: 1

categories: Contact Us

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

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
    <section class="section {{ $layout_classes }} ">
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

    <div class="mw-layout-container no-element container edit  safe-mode" field="layout-contacts-skin-1-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row col-12 col-lg-4 col-md-6 ms-auto p-3 cloneable element background-color-element allow-select regular-mode" style="background-color: #fff;">
            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Contact Us</h3>
            <p data-mwplaceholder="{{ _e('Enter text here') }}">We are here to help and answer any question you might have.</p>
             <module type="contact_form" template="skin-3"/>
        </div>
    </div>
 <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />

</section>
</div>

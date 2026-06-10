@php
/*

type: layout

name: Content 72

position: 72

categories: Content

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'mw-p-t-100';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'mw-p-b-50';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class=" section section-silver">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container-fluid edit  " field="layout-content-skin-72-{{ $params['id'] }}" rel="module">
        <div class="col-12 col-md-8 mx-auto   text-center mb-md-5   regular-mode">
            <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title Here</h3>
            <p data-mwplaceholder="{{ _e('Enter text here') }}" style="text-align-last: center; text-align: justify !important;">So we will freeze completely, until Amina, in this unfortunate, ridiculous fate.</p>
        </div>

        <div class="row safe-mode">
            <div class="col-12">
                <module type="slider"/>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>

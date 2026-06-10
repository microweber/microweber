@php
/*

type: layout

name: Content 33

position: 33

categories: Content

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


<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container mw-layout-container safe-mode no-element   edit " field="layout-content-skin-33-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-5 ">
            <div class="col-12 col-lg-8 col-lg-8 mx-auto   allow-drop allow-select">

                <div class="mw-micon-Batman-Mask safe-element element" style="font-size: 82px;"></div>


                <p data-mwplaceholder="{{ _e('Enter text here') }}">What if you "think" you don't know enough about your topic? Then, do some research. Read books and magazines. Do some searches on the Internet. Who knows? You might find an area, a niche, that is just waiting for you to fill it with useful information.</p>

            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>

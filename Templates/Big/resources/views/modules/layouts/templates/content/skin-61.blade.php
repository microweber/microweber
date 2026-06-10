@php
/*

type: layout

name: Content 61

position: 61

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
    <div class="container-fluid px-0 mw-layout-container safe-mode no-element    edit  no-element" field="layout-content-skin-61-{{ $params['id'] }}" rel="module" style="min-height: 600px!important;">
        <div class="row position-relative">
            <div class="col-xl-5 col-lg-6   position-lg-absolute px-0" style="left: 5%; top: 20%; z-index: 1; min-height: 400px!important;">
                <div class="regular-mode mh-400 p-5 element no-drag background-color-element allow-select allow-drop" style="background-color: #000;">
                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}" style="color: #fff;">Become a Explorer</h4>
                    <p data-mwplaceholder="{{ _e('Enter title here') }}" style="color: #fff;">It is a long established fact that a reader will be distracted by
                        <br> the readable content of a page when looking at its layout.
                    </p>
                </div>
            </div>

            <div class="col-xl-6 col-lg-7 mx-auto allow-select allow-drop">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-16.jpg') }}" alt="" class="img-cover mw-100" style="min-height: 600px;">
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>

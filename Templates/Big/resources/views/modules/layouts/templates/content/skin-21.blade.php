@php
/*

type: layout

name: Content 21

position: 21

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
    <div class="container mw-layout-container safe-mode no-element   edit " field="layout-content-skin-21-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-12 mx-auto d-flex align-items-center background-color-element allow-select">
                <div class="row ">
                    <div class="col-auto pe-7 icon-size-64px cloneable element safe-mode allow-select allow-drop">
                        <i class="mw-icon safe-element no-typing element mw-micon-Flash-2" style="font-size:66px;"></i>
                    </div>

                    <div class="col regular-mode  allow-select allow-drop ">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title</h3>

                        <p data-mwplaceholder="{{ _e('Enter text here') }}">One of the earliest activities we engaged in when we first got into astronomy is the same one we like to show our children just as soon as their excitement about the night sky begins to surface. That is the fun of finding constellations. But finding constellations and using them to navigate the sky is a discipline.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>

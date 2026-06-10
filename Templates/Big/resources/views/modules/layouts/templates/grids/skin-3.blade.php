{{--
type: layout
name: Grid 3
position: 3
categories: Grids
--}}

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
if (page_title()) {
    $title = page_title();
}
@endphp

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-3-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="cube-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

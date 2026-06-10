{{--
type: layout
name: Grid 4
position: 4
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
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-4-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="w-100 cube-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="w-100 cube-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-9.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

{{--
type: layout
name: Grid 1
position: 1
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
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-1-{{ $params['id'] }}" rel="module">
        <div class="row safe-mode">
            <div class="col-12 col-sm-8 safe-mode img-as-background" style="min-height: 350px;">
                <img loading="lazy" class="me-sm-3" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt="">
            </div>
            <div class="col-12 col-sm-4 safe-mode img-as-background" style="min-height: 350px;">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt="">
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

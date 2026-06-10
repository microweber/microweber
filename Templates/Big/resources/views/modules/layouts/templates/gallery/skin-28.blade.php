{{--
type: layout
name: Gallery 28 - Masonry
description: Gallery 28 - Masonry
categories: Gallery
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .module-pictures {
        padding: 0 !important;
    }
</style>

<section class="section {{ $layout_classes }}">
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element edit" field="layout-gallery-skin-28-{{ $params['id'] }}" rel="module">
        <module type="pictures" template="skin-18"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

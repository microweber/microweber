{{--
type: layout
name: Design 23
position: 123
categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section mw-new-layouts-23">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-23-{{ $params['id'] }}" rel="module">
        <module type="tabs" template="skin-1"/>
    </div>
    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

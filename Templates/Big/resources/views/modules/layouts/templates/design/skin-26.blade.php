{{--
type: layout
name: Design 26
position: 102
categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section mw-new-layouts-26">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-25-{{ $params['id'] }}" rel="module">
        <div class="row">
            <module type="testimonials" template="skin-23"/>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

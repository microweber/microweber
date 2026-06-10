{{--
type: layout
name: Testimonials - Parallax
position: 21
categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'mw-p-t-80';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'mw-p-b-80';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section testimonials-21 mw-layout-dark-background d-flex mw-layout-parallax">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/salmon_and_mashrooms.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container align-self-center {{ $layout_classes }} edit" field="layout-skin-21-{{ $params['id'] }}" rel="module">
        <module type="testimonials" template="skin-15" project_name="Testimonials 1"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

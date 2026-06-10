{{-- 
type: layout
name: Testimonial 17
position: 17
categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-0';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-testimonials-skin-17-{{ $params['id'] }}" rel="module">
        <module type="testimonials" template="skin-6" project_name="Testimonials 1"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

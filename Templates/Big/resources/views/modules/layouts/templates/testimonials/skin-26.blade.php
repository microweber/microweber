{{--
type: layout
name: Testimonial Cards
position: 26
categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-testimonials-skin-26-{{ $params['id'] }}" rel="module">
        <div class="text-center mx-auto pb-4" style="max-width: 720px;">
            <h3>What Our Clients Say</h3>
            <p>Hear from the people who trust us with their business.</p>
        </div>
        <module type="testimonials" template="skin-23" project_name="Testimonials Cards"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
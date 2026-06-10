{{-- 
type: layout
name: Testimonial 25
position: 25
categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} pb-5">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-testimonials-skin-25-{{ $params['id'] }}" rel="module">
        <div class="row col-12 col-xl-10 mx-auto">
            <h2 class="mb-5">Don’t Believe <br> Me Check Client Says</h2>
            <div></div>
            <module type="testimonials" template="skin-20" project_name="Testimonials 1"/>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

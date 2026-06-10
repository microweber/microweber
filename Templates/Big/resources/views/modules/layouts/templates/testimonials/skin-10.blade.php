{{-- 
type: layout
name: Testimonial 10
position: 10
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
    <div class="mw-layout-container no-element container edit" field="layout-testimonials-skin-10-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                <h6>Testimonials</h6>
                <br /><br /><br />
            </div>
        </div>
        <div></div>
        <module type="testimonials" template="skin-5" project_name="Testimonials 1"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

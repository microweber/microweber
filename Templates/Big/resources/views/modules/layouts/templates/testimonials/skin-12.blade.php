{{-- 
type: layout
name: Testimonial 12
position: 12
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
    <div class="mw-layout-container no-element container edit" field="layout-testimonials-skin-12-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-12 col-lg-8 col-lg-6 mx-auto">
                <h3>Testimonials</h3>
                <p>Stu Unger is one of the biggest superstars to have emerged from the professional poker world.</p>
                <br /><br /><br />
            </div>
        </div>
        <div></div>
        <module type="testimonials" template="skin-9" project_name="Testimonials 1"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

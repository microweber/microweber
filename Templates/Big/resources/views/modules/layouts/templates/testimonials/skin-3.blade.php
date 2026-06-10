{{-- 
type: layout
name: Testimonial 3
position: 3
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
    <div class="mw-layout-container no-element container edit" field="layout-testimonials-skin-3-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                <h5>Pictures In The Sky</h5>
                <p>The $79 iWork ’08 appears to be a good deal for anyone needing an affordable office suite for the Mac. Apple has finally added a spreadsheet application. At first glance, Numbers is an elegant no-brainer for anyone migrating from Microsoft Excel.</p>
                <br /><br /><br />
            </div>
        </div>
        <div></div>
        <module type="testimonials" template="skin-10" project_name="Testimonials 1"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

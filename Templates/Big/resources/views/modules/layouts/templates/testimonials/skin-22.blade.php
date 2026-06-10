{{-- 
type: layout
name: Testimonial 22
position: 22
categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'p-t-70';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'p-b-70';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-tony-testimonials-22-{{ $params['id'] }}" rel="module">
        <div class="row justify-content-center">
            <div class="col-xl-10 ps-md-4 ms-md-1 py-5">
                <h2>Loved by businesses, and <br> individuals</h2>
            </div>
            <div class="col-xl-10 d-xl-flex flex-xl-wrap">
                <module type="testimonials" template="skin-16" style="max-width: 100%;" project_name="Testimonials 1"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

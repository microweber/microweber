@php
/*
type: layout
name: Header 35 - Slider
position: 35
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section py-0 d-flex align-items-center justify-content-center">
    <module type="background" data-background-color="#7A6ADA" id="background-layout--{{ $params['id'] ?? '' }}"/>
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-35-{{ $params['id'] ?? '' }}" rel="module">
        <div class="col-12 col-lg-10 allow-select mx-auto">
            <module type="slider" templaet="swiper-skin-1" />
        </div>
    </div>
</section>

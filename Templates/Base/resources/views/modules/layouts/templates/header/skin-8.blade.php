@php
/*
type: layout
name: Header 8 - Slider
position: 8
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

{{-- task-big2-hdr8-slider-overflow — overflow-hidden on the section clips an
     empty/unconfigured Swiper slider, whose .swiper-slide gets width ~2^25px and
     otherwise blows the page width out to 16M+px (catastrophic horizontal scroll
     on mobile AND desktop until slides are added). The section is a block bounded
     to the viewport, so clipping here can't leak page scroll. --}}
<section class="section py-0 d-flex align-items-center justify-content-center overflow-hidden">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}"/>
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-8-{{ $params['id'] ?? '' }}" rel="module">
        <x-row class="text-center">
            <div class="col-12 safe-mode col-sm-10 col-lg-6 mx-auto mb-5 px-0">
                <module class="allow-select" type="slider"/>
            </div>

            <div class="col-12 safe-mode col-sm-10 col-lg-6 mx-auto text-center text-lg-start d-flex align-items-center mb-5">
                <div class="ps-lg-5 allow-select">
                    <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                    <br/>

                    <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Button"/>
                </div>
            </div>
        </x-row>
    </div>
</section>

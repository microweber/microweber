@php
/*
type: layout
name: Header 5
position: 5
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />
    <div class="mw-layout-container py-4 container-fluid mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit mw-pointer-skip" field="layout-header-skin-5-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row allow-select">
            <div class="col-12 safe-mode mx-auto">
                <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                <div class="row d-flex justify-content-center mt-5">
                    <a href="#" class="px-0 w-150">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/content-39-1.jpg') }}" class="cloneable element" alt=""/>
                    </a>
                    <a href="#" class="px-0 w-150">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/content-39-2.jpg') }}" class="cloneable element" alt=""/>
                    </a>
                </div>
            </div>

            <div class="position-absolute bottom-0 w-100 text-center left-0">
                <a href="#" class="btn btn-outline-primary btn-sm mb-7">
                    <i class="mdi mdi-chevron-down icon-size-24px me-0"></i>
                </a>
            </div>
        </div>
    </div>
</section>

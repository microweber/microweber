@php
/*
type: layout
name: Header 2
position: 2
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
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-2-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center">
            <div class="col-12 safe-mode mx-auto text-white safe-mode allow-select">
                <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
            </div>
        </div>
    </div>
</section>

@php
/*
type: layout
name: Header 22
position: 22
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
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}"/>
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-22-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-12 safe-mode col-lg-10 col-lg-8 mx-auto">
                <div class="allow-select">
                    <h1 class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                    <div class="d-flex justify-content-center mt-7">
                        <module type="contact_form" template="subscribe-1"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

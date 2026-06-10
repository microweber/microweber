@php
/*
type: layout
name: Header 3
position: 3
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .header-3 .img-as-background img {
        width: 100% !important;
        height: 100% !important;
    }
</style>

<section class="section py-0 header-3">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}"/>
    <div class="mw-layout-container no-element edit mw-pointer-skip" field="layout-header-skin-3-{{ $params['id'] ?? '' }}" rel="module">
        <div class="d-flex align-items-center justify-content-center safe-mode mw-pointer-skip">
            <div class="col-12 safe-mode col-md-6">
                <div class="col-md-10 mx-auto m-4 safe-mode allow-select">
                    <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
                    <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Call to action"/>
                </div>
            </div>

            <div class="safe-mode col-md-6 allow-select pe-0">
                <div class="img-as-background" style="min-height: 100vh;">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-6.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

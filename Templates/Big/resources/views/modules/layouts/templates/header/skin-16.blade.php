@php
/*
type: layout
name: Header 16
position: 16
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
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}"/>
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-16-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="text-center">
                <div class="allow-select">
                    <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                    <br>

                    <div class="text-center safe-mode d-flex align-items-center justify-content-center gap-2">
                        <module type="btn" button_style="btn-primary" text="Button For Your Banner"/>
                        <module type="btn" button_style="btn-outline-primary" text="Button For Your Banner"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

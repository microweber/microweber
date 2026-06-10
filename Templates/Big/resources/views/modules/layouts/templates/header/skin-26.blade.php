@php
/*
type: layout
name: Header 26
position: 26
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section py-0 mw-layout-dark-background d-flex align-items-center justify-content-center">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-13.jpg') }}"/>
    <div class="container-fluid mw-layout-container py-4 mw-header-section-mh-100vh  edit" field="layout-header-skin-26-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-12 safe-mode d-flex justify-content-center mx-auto mb-10">
                <div class="allow-select mt-10">
                    <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                    <module type="btn" button_style="btn-outline-dark" button_size="btn-md px-5 py-4 mt-2" text="Start now"/>
                </div>
            </div>
        </div>
    </div>
</section>

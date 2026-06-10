@php
/*
type: layout
name: Header 1
position: 1
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

{{-- AI-100 / TICKET-AI-100 (cycle-99 2026-05-09): hero section
     background not rendering on mobile.
     Pre-fix the `mw-header-section-mh-100vh` min-height class lived
     on the INNER `<div class="mw-layout-container">`, not the outer
     `<section>`. The `<module type="background">` element absolutely-
     positions a full-height block inside the section, but the section
     itself only carried `.section { min-height: 20px }` — so the
     position:absolute height:100% block resolved to 20px on mobile
     and the white heading text sat invisibly on a near-zero white
     band. Moving the class to the outer `<section>` gives the
     absolutely-positioned background block a real containing-block
     height to anchor against. --}}
<section class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center mw-header-section-mh-100vh">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />
    <div class="mw-layout-container py-4 container d-flex align-items-center justify-content-center no-element edit  safe-mode" field="layout-header-skin-1-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center">
            <div class="col-12 mx-auto text-white allow-select regular-mode">
                <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
                <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Call to action"/>
            </div>
        </div>
    </div>
</section>

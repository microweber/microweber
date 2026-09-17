<?php
/*
type: layout
name: Header 1 - Centered Hero on Dark Image
position: 1
categories: Header
*/
?>

@php
    // Real double quotes via a bound PHP string so the background module tag
    // parses correctly (see jumbotron/skin-1 for why &quot; entities break it).
    $headerBackgroundAttrs = 'data-background-color="#00000066" data-background-image="' . asset('templates/bootstrap/img/hero.jpg') . '"';
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center"
    field-name="layout-header-skin-1"
    :has-spacers="false"
    :background-attrs="$headerBackgroundAttrs"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container py-5 d-flex align-items-center justify-content-center no-element"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="9" class="mx-auto text-white allow-select regular-mode">
            <span class="badge bg-primary bg-opacity-75 text-uppercase fw-semibold mb-3 px-3 py-2" data-mwplaceholder="Enter text here">New season 2026</span>
            <h1 data-mwplaceholder="Enter title here" class="display-2 fw-bold mb-4">Build a website your customers remember</h1>
            <p data-mwplaceholder="Enter text here" class="lead mb-5 mx-auto" style="max-width: 640px;">Launch a fast, beautiful online presence in minutes. No code, no compromise, just the tools you need to grow.</p>
            <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Get started free"/>
        </x-col>
    </x-row>
</x-layout-section>

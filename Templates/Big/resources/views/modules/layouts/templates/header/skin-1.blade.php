@php
/*
type: layout
name: Header 1
position: 1
categories: Header
*/
@endphp

@php
    $headerBackgroundAttrs = 'data-background-color="#00000060" data-background-image="' . asset('templates/big/img/layouts/gallery-1-2.jpg') . '"';
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center mw-header-section-mh-100vh"
    field-name="layout-header-skin-1"
    :has-spacers="false"
    :background-attrs="$headerBackgroundAttrs"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container py-4 d-flex align-items-center justify-content-center no-element"
>
    <x-row class="text-center">
        <x-col size="12" class="mx-auto text-white allow-select regular-mode">
            <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company</h1>
            <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
            <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Call to action"/>
        </x-col>
    </x-row>
</x-layout-section>

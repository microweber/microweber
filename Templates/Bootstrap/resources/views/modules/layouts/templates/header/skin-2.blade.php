<?php
/*
type: layout
name: Header 2 - Split Hero with Image
position: 2
categories: Header
*/
?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section header-skin-2-split"
    field-name="layout-header-skin-2"
    default-padding-top="pt-6"
    default-padding-bottom="pb-6"
>
    <x-row class="align-items-center g-5">
        <x-col size="12" size-lg="6" class="allow-select regular-mode">
            <span class="text-primary text-uppercase fw-semibold small d-block mb-3" data-mwplaceholder="Enter text here">Trusted by 10,000+ teams</span>
            <h1 data-mwplaceholder="Enter title here" class="display-4 fw-bold mb-4">The simplest way to grow your business online</h1>
            <p data-mwplaceholder="Enter text here" class="lead text-muted mb-4">From your first sale to your thousandth, our platform scales with you. Manage products, content and customers all in one place.</p>
            <div class="d-flex flex-wrap gap-3">
                <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Start now"/>
                <module type="btn" button_style="btn-outline-secondary" button_size="btn-lg px-4" text="See how it works"/>
            </div>
        </x-col>
        <x-col size="12" size-lg="6" class="text-center">
            <img src="{{ asset('templates/bootstrap/img/sections/main-home.jpg') }}" alt="Product preview" class="img-fluid rounded-4 shadow-lg" />
        </x-col>
    </x-row>
</x-layout-section>

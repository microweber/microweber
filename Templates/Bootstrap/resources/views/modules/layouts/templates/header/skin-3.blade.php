<?php
/*
type: layout
name: Header 3 - Minimal Light Hero
position: 3
categories: Header
*/
?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section header-skin-3-minimal text-center"
    field-name="layout-header-skin-3"
    default-padding-top="pt-6"
    default-padding-bottom="pb-6"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto allow-select regular-mode">
            <p class="text-uppercase text-primary fw-semibold small mb-3" style="letter-spacing: .15em;" data-mwplaceholder="Enter text here">Welcome to our studio</p>
            <h1 data-mwplaceholder="Enter title here" class="display-3 fw-bold mb-4">Design that speaks for your brand</h1>
            <p data-mwplaceholder="Enter text here" class="lead text-muted mb-5 mx-auto" style="max-width: 620px;">We craft clean, purposeful digital experiences that help ambitious companies stand out and connect with the people who matter most.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="View our work"/>
                <module type="btn" button_style="btn-link" button_size="btn-lg px-4" text="Contact us"/>
            </div>
        </x-col>
    </x-row>
</x-layout-section>

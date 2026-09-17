<?php
/*
type: layout
name: Gallery 2 - Three Column Grid
position: 2
categories: Gallery
*/
?>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section gallery-skin-2"
    field-name="layout-gallery-skin-2"
    default-padding-top="pt-6"
    default-padding-bottom="pb-6"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto text-center regular-mode">
            <h2 data-mwplaceholder="Enter title here" class="fw-bold mb-3">A glimpse of our world</h2>
            <p data-mwplaceholder="Enter text here" class="lead text-muted mb-5 mx-auto" style="max-width: 620px;">Every image tells a small part of our story. Explore the highlights below.</p>
        </x-col>
    </x-row>

    <x-row class="row-cols-2 row-cols-md-3 g-3">
        <x-col>
            <div class="ratio ratio-1x1 bg-light rounded shadow-sm"></div>
        </x-col>
        <x-col>
            <div class="ratio ratio-1x1 rounded shadow-sm" style="background: linear-gradient(135deg, #e9ecef, #ced4da);"></div>
        </x-col>
        <x-col>
            <div class="ratio ratio-1x1 bg-light rounded shadow-sm"></div>
        </x-col>
        <x-col>
            <div class="ratio ratio-1x1 rounded shadow-sm" style="background: linear-gradient(135deg, #e9ecef, #ced4da);"></div>
        </x-col>
        <x-col>
            <div class="ratio ratio-1x1 bg-light rounded shadow-sm"></div>
        </x-col>
        <x-col>
            <div class="ratio ratio-1x1 rounded shadow-sm" style="background: linear-gradient(135deg, #e9ecef, #ced4da);"></div>
        </x-col>
    </x-row>
</x-layout-section>

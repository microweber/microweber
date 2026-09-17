<?php
/*
type: layout
name: Gallery 3 - Featured Plus Grid
position: 3
categories: Gallery
*/
?>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section gallery-skin-3"
    field-name="layout-gallery-skin-3"
    default-padding-top="pt-6"
    default-padding-bottom="pb-6"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto text-center regular-mode">
            <h2 data-mwplaceholder="Enter title here" class="fw-bold mb-3">Featured work</h2>
            <p data-mwplaceholder="Enter text here" class="lead text-muted mb-5 mx-auto" style="max-width: 620px;">A standout project alongside the details that make it shine.</p>
        </x-col>
    </x-row>

    <x-row class="g-3 align-items-stretch">
        <x-col size="12" size-lg="6">
            <div class="ratio ratio-1x1 bg-light rounded shadow-sm h-100"></div>
        </x-col>
        <x-col size="12" size-lg="6">
            <x-row class="row-cols-2 g-3">
                <x-col>
                    <div class="ratio ratio-4x3 bg-light rounded shadow-sm"></div>
                </x-col>
                <x-col>
                    <div class="ratio ratio-4x3 rounded shadow-sm" style="background: linear-gradient(135deg, #e9ecef, #ced4da);"></div>
                </x-col>
                <x-col>
                    <div class="ratio ratio-4x3 rounded shadow-sm" style="background: linear-gradient(135deg, #e9ecef, #ced4da);"></div>
                </x-col>
                <x-col>
                    <div class="ratio ratio-4x3 bg-light rounded shadow-sm"></div>
                </x-col>
            </x-row>
        </x-col>
    </x-row>
</x-layout-section>

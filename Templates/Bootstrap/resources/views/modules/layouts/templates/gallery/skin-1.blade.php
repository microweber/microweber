<?php
/*
type: layout
name: Gallery 1 - Editable Photo Gallery
position: 1
categories: Gallery
*/
?>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section gallery-skin-1"
    field-name="layout-gallery-skin-1"
    default-padding-top="pt-6"
    default-padding-bottom="pb-6"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto text-center regular-mode">
            <p class="text-uppercase text-primary fw-semibold small mb-3" style="letter-spacing: .15em;" data-mwplaceholder="Enter text here">Our gallery</p>
            <h2 data-mwplaceholder="Enter title here" class="fw-bold mb-3">Moments worth sharing</h2>
            <p data-mwplaceholder="Enter text here" class="lead text-muted mb-5 mx-auto" style="max-width: 640px;">A curated look at our work, our people and the projects we are proud of. Click any image to add or replace your own.</p>
        </x-col>
    </x-row>

    <module type="pictures" id="{{ $params['id'] }}-pictures" template="skin-1"/>
</x-layout-section>

<?php
/*
type: layout
name: Titles 2 - Centered accent underline
position: 2
categories: Titles
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section titles-skin-2"
    field-name="layout-titles-skin-2"
>
    <x-row class="text-center">
        <x-col size-lg="8" class="mx-auto">
            <h2 class="fw-bold mb-3" data-mwplaceholder="Enter title here">What we do best</h2>
            <span class="d-block mx-auto bg-primary mb-3" style="width:60px;height:3px;"></span>
            <p class="lead text-muted mb-0" data-mwplaceholder="Enter text here">A short introduction that sets the tone for the section below and tells visitors what to expect.</p>
        </x-col>
    </x-row>
</x-layout-section>

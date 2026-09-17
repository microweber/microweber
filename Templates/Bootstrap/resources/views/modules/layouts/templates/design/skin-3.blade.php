<?php
/*
type: layout
name: Design 3 - Breathing Room
position: 3
categories: Design
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section design-skin-3 py-5"
    field-name="layout-design-skin-3"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <span class="d-inline-block mb-3 text-primary">
                <i class="mw-micon-Star fs-3"></i>
            </span>
            <h3 class="h4 fw-semibold mb-2" data-mwplaceholder="Enter title here">A moment to breathe</h3>
            <p class="text-secondary mb-0" data-mwplaceholder="Enter text here">Add gentle vertical rhythm between your content blocks so each section stands on its own.</p>
        </x-col>
    </x-row>

    <module type="spacer" id="{{ $params['id'] }}-spacer1"/>
</x-layout-section>

<?php
/*
type: layout
name: Design 2 - Pull Quote
position: 2
categories: Design
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section design-skin-2 py-6"
    field-name="layout-design-skin-2"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="9" size-xl="8" class="mx-auto">
            <span class="d-inline-block mb-3 text-primary">
                <i class="mw-micon-Quotes fs-1"></i>
            </span>
            <blockquote class="blockquote fs-2 fw-light mb-4">
                <p class="mb-0" data-mwplaceholder="Enter quote here">Great design is not about decoration. It is about clarity, rhythm and giving every idea the space it deserves to breathe.</p>
            </blockquote>
            <footer class="blockquote-footer fs-6" data-mwplaceholder="Enter attribution">Jane Cooper, <cite title="Role">Creative Director</cite></footer>
        </x-col>
    </x-row>
</x-layout-section>

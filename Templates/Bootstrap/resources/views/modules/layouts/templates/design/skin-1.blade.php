<?php
/*
type: layout
name: Design 1 - Icon Divider
position: 1
categories: Design
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section design-skin-1 py-5"
    field-name="layout-design-skin-1"
>
    <x-row>
        <x-col size="12" size-lg="8" class="mx-auto">
            <div class="position-relative text-center my-3">
                <hr class="border-2 border-secondary opacity-50 m-0">
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-secondary">
                    <i class="mw-micon-Diamond fs-4"></i>
                </span>
            </div>
        </x-col>
    </x-row>
</x-layout-section>

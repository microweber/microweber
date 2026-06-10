<?php
/*
type: layout
name: Feature 4
position: 4
categories: Features
*/
?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-feature-skin-4"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto regular-mode">
            <x-section-heading tag="h3">The Feature Title</x-section-heading>
        </x-col>
    </x-row>

    <x-row class="text-center mt-sm-5 mt-3">
        <x-feature-item icon="mw-micon-Android-Store" title="Feature Title" text="Speaking comes to most people as naturally as breathing. On many occasions our words are." col-class="col-sm-6 col-md-3" class="mx-auto" />

        <x-feature-item icon="mw-micon-Add" title="Feature Title" text="Speaking comes to most people as naturally as breathing. On many occasions our words are." col-class="col-sm-6 col-md-3" class="mx-auto" />

        <x-feature-item icon="mw-micon-Add-Window" title="Feature Title" text="Speaking comes to most people as naturally as breathing. On many occasions our words are." col-class="col-sm-6 col-md-3" class="mx-auto" />
    </x-row>
</x-layout-section>

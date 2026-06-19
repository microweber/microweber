{{--
type: layout

name: Titles 1

position: 1

categories: Titles
--}}

<style>
    .titles-1 .mw-breadcrumb {
        justify-content: center;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section titles-1"
    field-name="layout-titles-skin-1"
>
    <x-row class="text-center mb-5">
        <x-col size-lg="10" class="mx-auto regular-mode text-center">
            <module type="breadcrumb"/>
            <x-section-heading tag="h1" subtitle="It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout." class="mb-3">Design Concept</x-section-heading>
        </x-col>
    </x-row>
</x-layout-section>

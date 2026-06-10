@php
/*

type: layout

name: Content 2

position: 2

categories: Content

*/
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-2"
    :no-drop="true"
    container-class="mw-layout-container safe-mode no-element text-center"
>
    <x-row class="nodrop no-select">
        <x-col size="12" size-lg="8" class="mx-auto allow-select allow-drop" style="min-height: 40px">
            <div class="mb-4 no-element">
                <i class="safe-element no-typing mw-micon-Anchor mb-4 icon-size-64px"></i>
            </div>
            <div class="regular-mode">
                <x-section-heading tag="h3" subtitle="Update your audience on new developments and how you're overcoming challenges.">Your Story Should Evolve Over Time</x-section-heading>
            </div>
        </x-col>
    </x-row>
</x-layout-section>

<?php

/*

type: layout

name: Content 1

position: 1

categories: Content

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-1"
    container-class="mw-layout-container text-center"
>
    <x-row>
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="mb-4 no-element">
                <i class="safe-element no-typing mw-micon-Anchor mb-4 icon-size-64px"></i>
            </div>
            <div class="regular-mode">
                <x-section-heading tag="h3" subtitle="Update your audience on new developments and how you're overcoming challenges.">Your Story Should Evolve Over Time</x-section-heading>
            </div>
        </x-col>
    </x-row>
</x-layout-section>

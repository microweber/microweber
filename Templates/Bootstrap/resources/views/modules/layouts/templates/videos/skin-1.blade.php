<?php
/*
type: layout
name: Videos 1 - Centered feature
position: 1
categories: Videos
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section videos-skin-1"
    field-name="layout-videos-skin-1"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Watch our story unfold and see what makes us different.">
                Take a look inside
            </x-section-heading>
            <p class="lead text-muted" data-mwplaceholder="Write a short intro for your video">
                Press play to discover how our team builds products people love, straight from the people who make it happen.
            </p>
        </x-col>
    </x-row>

    <x-row class="mt-4">
        <x-col size="12" size-lg="10" class="mx-auto">
            <module type="video" id="{{ $params['id'] }}-video1"/>
        </x-col>
    </x-row>
</x-layout-section>

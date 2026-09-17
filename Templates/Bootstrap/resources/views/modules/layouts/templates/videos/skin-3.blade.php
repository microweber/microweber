<?php
/*
type: layout
name: Videos 3 - Dark highlight band
position: 3
categories: Videos
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section videos-skin-3 bg-dark text-white"
    field-name="layout-videos-skin-3"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <h2 class="fw-bold mb-3" data-mwplaceholder="Add a heading">
                Watch the highlight reel
            </h2>
            <p class="lead text-white-50 mb-4" data-mwplaceholder="Add a short description">
                One minute is all it takes to see what we are about. Hit play and enjoy the show.
            </p>
        </x-col>
    </x-row>

    <x-row>
        <x-col size="12" size-lg="9" class="mx-auto">
            <div class="ratio ratio-16x9 bg-dark rounded d-flex align-items-center justify-content-center">
                <i class="mw-micon-Play text-white fs-1"></i>
            </div>
            <div class="text-center mt-4">
                <module type="btn" id="{{ $params['id'] }}-btn3" button_style="btn-light" button_size="btn-lg" button_text="Watch now"/>
            </div>
        </x-col>
    </x-row>
</x-layout-section>

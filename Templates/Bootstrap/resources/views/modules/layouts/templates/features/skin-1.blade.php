<?php

/*

type: layout

name: Features 1

position: 1

categories: Features

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-2"
    field-name="layout-features-skin-1"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h4">The Feature Title</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="text-center mt-7">
        <x-feature-item icon="mw-micon-Add-User" text="To get started in learning how to observe the stars much better, there are some basic things.">
            <div class="mt-md-4 mt-3">
                <module type="btn" id="{{ $params['id'] }}-btn-1" button_style="btn-dark" button_size="btn-md" button_text="Learn More"/>
            </div>
        </x-feature-item>

        <x-feature-item icon="mw-micon-Add-UserStar" text="To get started in learning how to observe the stars much better, there are some basic things.">
            <div class="mt-md-4 mt-3">
                <module type="btn" id="{{ $params['id'] }}-btn-2" button_style="btn-dark" button_size="btn-md" button_text="Learn More"/>
            </div>
        </x-feature-item>

        <x-feature-item icon="mw-micon-Business-ManWoman" text="To get started in learning how to observe the stars much better, there are some basic things.">
            <div class="mt-md-4 mt-3">
                <module type="btn" id="{{ $params['id'] }}-btn-3" button_style="btn-dark" button_size="btn-md" button_text="Learn More"/>
            </div>
        </x-feature-item>
    </x-row>
</x-layout-section>

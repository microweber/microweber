{{--
type: layout

name: Videos 4

position: 4

categories: Videos
--}}

@php
    $videoBackgroundAttrs = 'data-background-color="#ddd"';
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-video-skin-4"
    :background-attrs="$videoBackgroundAttrs"
    container-class="mw-layout-container d-flex align-items-center justify-content-center no-element"
>
    <x-row class="text-center">
        <x-col size="12" size-md="10" class="mx-auto regular-mode">
            <x-section-heading tag="h1" subtitle="Authenticity is key. Share your journey, your passion, and the real reason behind your venture. People connect with genuine stories.">Share story with a video.</x-section-heading>
            <x-video-embed url="{{ asset('templates/big/videos/example.mp4') }}" class="safe-mode" />
        </x-col>
    </x-row>
</x-layout-section>


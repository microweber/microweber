{{--
type: layout

name: Videos 1

position: 1

categories: Videos
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-video-skin-1"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="10" class="mx-auto regular-mode">
            <x-section-heading tag="h1" subtitle="Authenticity is key. Share your journey, your passion, and the real reason behind your venture. People connect with genuine stories.">Share your story <br> with a video</x-section-heading>
            <x-video-embed url="{{ asset('templates/big/videos/example.mp4') }}" height="500" class="module-padding-for-handle safe-mode" />
        </x-col>
    </x-row>
</x-layout-section>


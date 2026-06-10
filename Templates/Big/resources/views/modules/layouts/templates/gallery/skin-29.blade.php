@php
/*
type: layout
name: Media Card Gallery
position: 29
categories: Gallery
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-gallery-skin-29-{{ $params['id'] }}" rel="module">
        <div class="text-center mx-auto pb-4" style="max-width: 720px;">
            <h3 class="regular-mode" data-mwplaceholder="Enter title here">Our Gallery</h3>
            <p class="regular-mode" data-mwplaceholder="Enter text here">Browse through our collection of images and media.</p>
        </div>

        <x-row class="g-4 safe-mode">
            <x-col size="12" size-md="6" size-lg="4">
                <x-media-card
                    title="Creative Design"
                    description="Exploring new design possibilities."
                    media-type="image"
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="12" size-md="6" size-lg="4">
                <x-media-card
                    title="Video Showcase"
                    description="Watch our latest video production."
                    media-type="video"
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="12" size-md="6" size-lg="4">
                <x-media-card
                    title="Photography"
                    description="Beautiful moments captured in time."
                    media-type="image"
                    class="shadow-sm h-100"
                />
            </x-col>
        </x-row>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
{{--
type: layout
name: Media Card Gallery
position: 29
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-29"
    container-class="mw-layout-container no-element container edit safe-mode"
>
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
</x-layout-section>

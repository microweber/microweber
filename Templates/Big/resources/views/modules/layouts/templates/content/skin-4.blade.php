@php
/*

type: layout

name: Content 4

position: 4

categories: Content

*/
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-4"
    container-class="mw-layout-container safe-mode no-element"
>
    <x-row class="text-center mb-3 nodrop no-select">
        <x-col size="12" size-lg="8" class="mx-auto">
            <div class="regular-mode allow-select allow-drop">
                <x-section-heading tag="h3" subtitle="Remember, your story is a dynamic tool that can evolve and adapt as your venture progresses. The way you tell your story online can indeed make a significant difference in building connections, generating interest, and achieving your goals.">A Great Title For This Section</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="mb-3 py-4 nodrop no-select">
        <x-feature-item title="First Title" text="Point of Sale hardware, the till at a shop check out, has become very complex" col-class="col-12 col-md-6 col-lg-3" class="mb-md-4 p-4 d-flex flex-column allow-select allow-drop">
            <module type="btn" button_style="btn-primary" text="Learn more"/>
        </x-feature-item>

        <x-feature-item title="Second Title" text="Point of Sale hardware, the till at a shop check out, has become very complex" col-class="col-12 col-md-6 col-lg-3" class="mb-md-4 p-4 d-flex flex-column allow-select allow-drop">
            <module type="btn" button_style="btn-primary" text="Learn more"/>
        </x-feature-item>

        <x-feature-item title="Third Title" text="Point of Sale hardware, the till at a shop check out, has become very" col-class="col-12 col-md-6 col-lg-3" class="mb-md-4 p-4 d-flex flex-column allow-select allow-drop">
            <module type="btn" button_style="btn-primary" text="Learn more"/>
        </x-feature-item>

        <x-feature-item title="Last Title" text="Point of Sale hardware, the till at a shop check out, has become very complex" col-class="col-12 col-md-6 col-lg-3" class="mb-md-4 p-4 d-flex flex-column allow-select allow-drop">
            <module type="btn" button_style="btn-primary" text="Learn more"/>
        </x-feature-item>
    </x-row>
</x-layout-section>

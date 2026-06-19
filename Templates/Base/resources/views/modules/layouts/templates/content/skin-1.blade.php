@php
/*

type: layout

name: Content 1

position: 1

categories: Content

*/
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-1"
    container-class="mw-layout-container safe-mode no-element text-center"
>
    <div data-edit-ref="layout-content-skin-1-{{ $params['id'] }}">
        <x-row class="text-center safe-mode">
            <x-col size="12" size-lg="8" class="mx-auto regular-mode allow-drop allow-select">
                <x-section-heading tag="h3" subtitle="Update your audience on new developments and how you're overcoming challenges.">Your Story Should Evolve Over Time</x-section-heading>
            </x-col>
        </x-row>

        <x-row class="text-center safe-mode mt-5">
            <div class="col cloneable element mb-5 allow-drop allow-select">
                <img loading="lazy" class="rounded-3 h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
            </div>
            <div class="col cloneable element mb-5 allow-drop allow-select">
                <img loading="lazy" class="rounded-3 h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-6.jpg') }}" alt=""/>
            </div>
            <div class="col cloneable element mb-5 allow-drop allow-select">
                <img loading="lazy" class="rounded-3 h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
            </div>
            <div class="col cloneable element mb-5 allow-drop allow-select">
                <img loading="lazy" class="rounded-3 h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
            </div>
        </x-row>

        <div class="safe-mode allow-select">
            <module type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>
        </div>
    </div>
</x-layout-section>

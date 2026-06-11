@php
    $mwBgAttrs = 'data-parallax="true" data-overlay-x="1" data-background-color="#00000060" data-background-image="' . asset('templates/big/img/layouts/gallery-1-5.jpg') . '"';
@endphp
{{--
type: layout
name: Feature 66 - Parallax
position: 66
categories: Features
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section feature-66 mw-layout-parallax mw-layout-dark-background mh-350 d-flex align-items-center justify-content-center"
    field-name="layout-feature-skin-66"
    :background-attrs="$mwBgAttrs"
    container-class="mw-layout-container container no-element edit"
>
    <x-row class="text-center justify-content-center align-items-center">
        <x-col size-lg="3">
            <h2>232</h2>
            <p>Clients</p>
        </x-col>
        <x-col size-lg="3">
            <h2>521</h2>
            <p>Projects</p>
        </x-col>
        <x-col size-lg="3">
            <h2>1453</h2>
            <p>Hour Of Support</p>
        </x-col>
        <x-col size-lg="3">
            <h2>32</h2>
            <p>Workers</p>
        </x-col>
    </x-row>
</x-layout-section>

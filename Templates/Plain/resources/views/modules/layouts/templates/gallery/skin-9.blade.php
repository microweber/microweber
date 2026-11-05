{{--
type: layout
name: Gallery 9
position: 9
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-overlay-wrapper"
    :has-background="false"
    container-class="mw-layout-container no-element"
>
    <module type="slider"/>
</x-layout-section>

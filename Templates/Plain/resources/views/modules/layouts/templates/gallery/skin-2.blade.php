{{--
type: layout
name: Gallery 2
position: 2
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-2"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <module type="slider"/>
</x-layout-section>

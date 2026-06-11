{{--
type: layout
name: Gallery 14
position: 14
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-14"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <module type="pictures" template="skin-5" id="gallery-{{ $params['id'] }}" />
</x-layout-section>

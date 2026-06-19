{{--
type: layout
name: Header 33 - Slider
position: 33
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-header-skin-33"
    :has-background="false"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element edit"
>
    <module class="allow-select" type="slider"/>
</x-layout-section>

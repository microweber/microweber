{{--
type: layout

name: Default

position: 0

categories: Default
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-1"
    container-class="mw-layout-container container no-element edit"
>
    <module type="posts" template="default" />
</x-layout-section>

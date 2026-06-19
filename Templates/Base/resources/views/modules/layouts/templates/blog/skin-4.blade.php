{{--
type: layout

name: Blog 4

position: 4

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-4"
    container-class="mw-layout-container container no-element edit"
>
    <module type="posts" template="skin-4" slides-md="2" slides-lg="3" slides-lg="3" adaptive_height="false" />
</x-layout-section>

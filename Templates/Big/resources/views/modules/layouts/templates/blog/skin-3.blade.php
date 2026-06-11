{{--
type: layout

name: Blog 3

position: 3

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-3"
    container-class="mw-layout-container container no-element edit"
>
    <module type="posts" template="skin-3" />
</x-layout-section>

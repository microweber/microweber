{{--
type: layout

name: Blog 2

position: 2

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-2"
    container-class="mw-layout-container container no-element edit"
>
    <module type="posts" template="skin-2" />
</x-layout-section>

{{--
type: layout

name: Blog 11

position: 11

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-11"
    container-class="mw-layout-container container no-element edit"
>
    <module type="posts" template="skin-11" limit="1" order_by="position desc" />
</x-layout-section>

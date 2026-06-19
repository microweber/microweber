{{--
type: layout

name: Blog 18

position: 18

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-18"
    container-class="mw-layout-container container-fluid no-element edit"
>
    <x-row class="col-xl-12 mx-auto">
                <module type="posts" template="blog-pro" />
            </x-row>
</x-layout-section>

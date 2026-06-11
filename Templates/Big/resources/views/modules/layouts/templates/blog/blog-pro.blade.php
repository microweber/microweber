{{--
type: layout

name: Blog blog-pro

position: blog-pro

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-blog-pro"
    container-class="mw-layout-container container-fluid no-element edit"
>
    <div class="blog-title mt-3 mb-5">
                <h1>Our Latest Blog</h1>
            </div>
            <module type="posts" template="blog-pro"/>
</x-layout-section>

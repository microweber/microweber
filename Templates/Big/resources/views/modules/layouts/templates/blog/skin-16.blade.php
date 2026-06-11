{{--
type: layout

name: Blog 16

position: 16

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-16"
    container-class="mw-layout-container container-fluid no-element edit"
>
    <div class="col-xl-10 mx-auto">
                <div class="mx-auto text-center mb-8">
                    <h1 class="mb-3" style="font-size: 42px; color: #181E4E;">Our Latest Episodes</h1>
                </div>
                <module type="posts" template="skin-16" />

                <div class="d-flex justify-content-center">
                    <module type="btn" button_text="All Episodes" />
                </div>
            </div>
</x-layout-section>

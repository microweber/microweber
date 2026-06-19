{{--
type: layout

name: Blog 17

position: 17

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-17"
    container-class="mw-layout-container container-fluid no-element edit"
>
    <div class="col-xl-10 mx-auto">
                <div class="text-start mb-8">
                    <h1 class="mb-3" style="font-size: 42px; color: #181E4E;">Top Podcast <br> For This Week</h1>
                    <p style="color: #737272;">Sed ut perspiciais unde omnish iste natus error <br> sit voluptatemaccusantium.</p>
                </div>
                <module type="posts" template="skin-17" />
                <div class="d-flex justify-content-center">
                    <module type="btn" button_text="Explore more" />
                </div>
            </div>
</x-layout-section>

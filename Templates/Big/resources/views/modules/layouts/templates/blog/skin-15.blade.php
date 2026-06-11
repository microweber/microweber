{{--
type: layout

name: Blog 15

position: 15

categories: Blog
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background"
    field-name="layout-blog-skin-15"
    container-class="mw-layout-container container-fluid no-element edit"
>
    <div class="col-md-10 mx-auto">
                <div class="mx-auto text-center mb-5 d-lg-flex justify-content-between">
                    <h1 class="mb-3" style="font-size: 64px;">My Project</h1>
                    <div>
                        <p>Grab the opportunity to capture memories that you will treasure for
                            <br> a safetime. Why be ordinary when you can extraordinary?</p>

                        <div class="d-flex align-items-center justify-content-end cloneable">
                            <module type="btn" button_text="See All Moments ->" button_style="btn btn-link px-5 py-4 text-decoration-underline" />
                        </div>
                    </div>
                </div>
                <module type="posts" template="skin-15" />
            </div>
</x-layout-section>

{{--
type: layout

name: Blog 14

position: 14

categories: Blog
--}}

<style>
    [field="layout-blog-skin-14-{{ $params['id'] ?? '' }}"] h1 {
        font-size: 64px;
    }

    [field="layout-blog-skin-14-{{ $params['id'] ?? '' }}"] [template="skin-14"] .img-as-background img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    [field="layout-blog-skin-14-{{ $params['id'] ?? '' }}"] [template="skin-14"] .img-as-background {
        position: relative;
        overflow: hidden;
        height: 400px;
    }

    @media (max-width: 1400px) {
        [field="layout-blog-skin-14-{{ $params['id'] ?? '' }}"] [template="skin-14"] .img-as-background {
            height: 350px;
        }
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-container"
    container-class="mw-layout-container container-fluid no-element"
>
    <div class="col-md-10 mx-auto edit" field="layout-blog-skin-14-{{ $params['id'] ?? '' }}" rel="module">
                <div class="mx-auto text-center mb-8">
                    <h1 class="mb-3">Beautiful Moments That i Captured</h1>
                    <p>I photograph beautiful things everywhere, born of boredom. Raised by longing, for those of
                        <br> you who love presentations that make you dizzy and keep your spirits up.
                    </p>
                </div>

                <module type="posts" template="skin-14" />

                <div class="d-flex align-items-center justify-content-center cloneable">
                    <module type="btn" button_text="See All Moments ->" button_style="btn btn-primary px-5 py-4" />
                </div>
            </div>
</x-layout-section>

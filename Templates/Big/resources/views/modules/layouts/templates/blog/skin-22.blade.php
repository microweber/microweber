{{--
type: layout

name: Blog 22

position: 22

categories: Blog
--}}

<style>
    .mw-blog-22-avatar-image-wrapper img {
        border-radius: 100px;
        width: 160px !important;
        height: 160px !important;
        object-fit: cover;
    }

    .section-title-wrap {
        background: var(--mw-primary-color);
        border-radius: 10px;
        padding: 10px 30px;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section projects"
    field-name="layout-blog-skin-22"
    container-class="mw-layout-container container no-element edit"
>
    <x-row>
                <div class="col-lg-8 col-md-8 col-12 ms-auto">
                    <div class="section-title-wrap d-flex justify-content-center align-items-center mb-4 mw-blog-22-avatar-image-wrapper background-color-element element">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/white-desk-work-study-aesthetics.jpg') }}" alt="" />
                        <h2 data-mwplaceholder="@php _e('Enter title here'); @endphp" class="text-white ms-4 mb-0">Projects</h2>
                    </div>
                </div>
                <div class="clearfix"></div>
                <module type="posts" template="skin-23" />
            </x-row>
</x-layout-section>

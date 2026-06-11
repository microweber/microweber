{{--
type: layout
name: Content 83
position: 83
categories: Content
--}}

<style>
    .profile-body p {
        margin-bottom: 0;
    }

    .profile-body p:nth-of-type(even) {
        background: #fff;
    }

    .mw-content-83-about-image {
        border-radius: 20px;
    }

    .mw-content-83-about-thumb {
        padding-right: 20px;
        padding-left: 20px;
    }

    .mw-content-83-section-title-wrap {
        background-color: var(--mw-primary-color);
        border-radius: 10px;
        padding: 10px 30px;
    }

    .mw-content-83-avatar-image-wrapper img {
        border-radius: 100px;
        width: 160px !important;
        height: 160px !important;
        object-fit: cover;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-content-83-about section-content-83 pb-0"
    field-name="layout-content-skin-83"
    container-class="mw-layout-container safe-mode no-element edit container"
>
    <x-row>
        <x-col size-lg="6" class="safe-mode allow-select">
            <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/couple-working-from-home-together-sofa.jpg') }}" class="mw-content-83-about-image img-fluid" alt=""/>
        </x-col>

        <x-col size-lg="6" class="mt-5 mt-lg-0 allow-select">
            <div class="mw-content-83-about-thumb safe-mode">
                <div class="mw-content-83-section-title-wrap background-color-element element d-flex justify-content-end align-items-center mb-4 mw-content-83-avatar-image-wrapper">
                    <h2 data-mwplaceholder="@lang('Enter title here')" class="text-white me-4 mb-0">My Story</h2>
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/happy-bearded-young-man.jpg') }}" alt=""/>
                </div>

                <h3 data-mwplaceholder="@lang('Enter title here')" class="pt-2 mb-3 font-weight-bold">a little bit about Joshua</h3>

                <p data-mwplaceholder="@lang('Enter title here')">This one-page HTML portfolio is provided by
                    <a href="" target="_blank">CMS</a>.
                    This layout is based on Bootstrap v5.1.3 CSS and JS libraries. Image credits go to
                    <a href="https://unsplash.com" target="_blank">Unsplash</a> and <a href="https://freepik.com" target="_blank">FreePik</a>
                    for images used in this page.
                </p>

                <p data-mwplaceholder="@lang('Enter title here')">You are allowed to use this template for your websites. You are not allowed to redistribute the template ZIP file on any other website. Please <a href="" target="_blank">contact us</a> for more info.</p>
            </div>
        </x-col>
    </x-row>
</x-layout-section>

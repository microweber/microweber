{{--
type: layout

    name: Content 87

    position: 87

    categories: Content
--}}

<style>
    .content-87-wrapper {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: stretch;
        padding-inline: 0 !important;
    }

    .content-87-wrapper img {
        height: 100% !important;
        object-fit: cover;
    }

    .content-87-text-wrapper {
        position: absolute;
        background-color: rgb(var(--mw-primary-color) / .7);
        display: flex;
        justify-content: center;
        align-items: center;
        left: 0;
        right: 0;
        bottom: 0;
        height: 100px;
    }

    .content-87-wrapper p {
        padding: 20px;
    }

    @media (min-width: 768px) {
        .content-87-text-wrapper-bottom {
            top: 0;
            bottom: unset;
        }
    }

    .content-87-content-wrapper, .content-87-p-wrapper {
        position: relative;
        min-height: 350px !important;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section section-content-87"
    field-name="layout-content-skin-87"
    container-class="mw-layout-container safe-mode no-element edit"
>
    <x-row class="cloneable element">
                <div class="col-lg-3 col-md-6 col-12 content-87-wrapper">
                    <div class="content-87-content-wrapper safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-9.jpg') }}" alt="" />
                        <div class="content-87-text-wrapper background-color-element element">
                            <div class="mw-layout-dark-background p-2">
                                <h4 class="mb-0 font-weight-bold">Web Design</h4>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0 p-xl-4 p-3">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                        Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                        <br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                        Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 col-12 content-87-wrapper">
                    <div class="content-87-content-wrapper safe-mode order-sm-2 order-1">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt="" />
                        <div class="content-87-text-wrapper content-87-text-wrapper-bottom background-color-element element" style="background-color: rgb(var(--mw-primary-color) / .7);">
                            <div class="mw-layout-dark-background p-2">
                                <h4 class="mb-0 font-weight-bold">Marketing</h4>
                            </div>
                        </div>
                    </div>
                    <div class="content-87-p-wrapper order-sm-1 order-2">
                        <p class="mb-0 p-xl-4 p-3">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                            Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                            <br><br>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                            Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 content-87-wrapper">
                    <div class="content-87-content-wrapper safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}" alt="" />
                        <div class="content-87-text-wrapper background-color-element element">
                            <div class="mw-layout-dark-background p-2">
                                <h4 class="mb-0 font-weight-bold">Web Development</h4>
                            </div>
                        </div>
                    </div>
                    <p class="mb-0 p-xl-4 p-3">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                        Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                        <br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                        Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 col-12 content-87-wrapper">
                    <div class="content-87-content-wrapper safe-mode order-sm-2 order-1">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-6.jpg') }}" alt="" />
                        <div class="content-87-text-wrapper content-87-text-wrapper-bottom background-color-element element" style="background-color: rgb(var(--mw-primary-color) / .6);">
                            <div class="mw-layout-dark-background p-2">
                                <h4 class="mb-0 font-weight-bold">Branding</h4>
                            </div>
                        </div>
                    </div>
                    <div class="content-87-p-wrapper order-sm-1 order-2">
                        <p class="mb-0 p-xl-4 p-3">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                            Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                            <br><br>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat.
                            Aenean eu felis nisi. Suspendisse quis porta tortor, id cursus mauris. Nulla facilisi.
                        </p>
                    </div>
                </div>
            </x-row>
</x-layout-section>

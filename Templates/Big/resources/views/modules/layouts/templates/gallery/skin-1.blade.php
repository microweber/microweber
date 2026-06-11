{{--
type: layout
name: Gallery 1
position: 1
categories: Gallery
--}}

<style>
    .gallery-skin-1 img {
        height: 100% !important;
        width: 100% !important;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section gallery-skin-1"
    field-name="layout-gallery-skin-1"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="m-0 no-element">
                <div class="d-flex flex-wrap pe-md-5 pb-5 col-12 col-lg-6 no-element">
                    <div class="col-6 pe-2">
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt="">
                        </div>
                    </div>

                    <div class="col-6 ps-2">
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt="">
                        </div>
                    </div>

                    <div class="col-12 pt-5">
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt="">
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap col-12 col-lg-6 pe-md-5 pb-5 no-element">
                    <div class="col-12 pb-5">
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt="">
                        </div>
                    </div>

                    <div class="col-6 pe-2">
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt="">
                        </div>
                    </div>

                    <div class="col-6 ps-2">
                        <div class="img-as-background square">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-6.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

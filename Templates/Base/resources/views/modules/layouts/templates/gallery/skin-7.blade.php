{{--
type: layout
name: Gallery 7
position: 7
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-7"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center">
                <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                    </div>
                    <div class="py-4 mt-md-auto">
                        <h5 class="mb-2">Pictures In The Sky</h5>
                        <p>History of modern astronomy, there is probably no one.</p>
                    </div>
                </div>

                <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                    </div>
                    <div class="py-4 mt-md-auto">
                        <h5 class="mb-2">Radio Astronomy</h5>
                        <p>History of modern astronomy, there is probably no one.</p>
                    </div>
                </div>

                <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                    </div>
                    <div class="py-4 mt-md-auto">
                        <h5 class="mb-2">The Amazing Hubble</h5>
                        <p>History of modern astronomy, there is probably no one.</p>
                    </div>
                </div>

                <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt=""/>
                    </div>
                    <div class="py-4 mt-md-auto">
                        <h5 class="mb-2">Look Up In The Sky</h5>
                        <p>History of modern astronomy, there is probably no one.</p>
                    </div>
                </div>
            </x-row>
</x-layout-section>

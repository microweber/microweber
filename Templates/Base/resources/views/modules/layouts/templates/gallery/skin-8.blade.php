{{--
type: layout
name: Gallery 8
position: 8
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-8"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center">
                <div class="mx-auto col-sm-10 col-md-6 mb-2 cloneable element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                    </div>
                </div>

                <div class="mx-auto col-sm-10 col-md-6 mb-2 cloneable element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                    </div>
                </div>
            </x-row>
</x-layout-section>

{{--
type: layout
name: Gallery 24
position: 24
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-24"
    :has-background="false"
    container-class="mw-layout-container no-element container-fluid p-0 edit"
>
    <x-row class="text-center">
                <div class="col-sm-10 col-md-6 cloneable element p-0">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                    </div>
                </div>

                <div class="col-sm-10 col-md-6 cloneable element p-0">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                    </div>
                </div>
            </x-row>
</x-layout-section>

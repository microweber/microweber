{{--
type: layout
name: Gallery 11
position: 11
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-11"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-12 col-lg-10 mx-auto">
                    <x-row class="text-center text-md-start">
                        <div class="mx-auto col-sm-10 col-md-6 mb-md-5 cloneable element">
                            <div class="d-flex flex-column">
                                <div class="img-as-background square">
                                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                                </div>
                                <div class="py-4">
                                    <p>For most of us, it’s a curiosity, an amusement to see what they say our day will be like based.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mx-auto col-sm-10 col-md-6 mt-0 mt-md-9 mb-md-5 cloneable element">
                            <div class="d-flex flex-column">
                                <div class="img-as-background square">
                                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                                </div>
                                <div class="py-4">
                                    <p>Having used discount toner cartridges for twenty years, there have been a lot of changes in the toner.</p>
                                </div>
                            </div>
                        </div>
                    </x-row>
                </div>
            </x-row>
</x-layout-section>

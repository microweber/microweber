{{--
 type: layout
 name: Feature 22
 position: 22
 categories: Features
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-feature-skin-22"
    container-class="mw-layout-container no-element container-fluid edit safe-mode"
>
    <x-row class="mh-650">
                <div class="img-as-background col-12 col-sm-10 col-lg-6 mh-400">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt="">
                </div>

                <div class="col-12 col-sm-10 col-lg-6 mx-auto text-center text-sm-start d-flex align-items-center cloneable element">
                    <div class="mw-layout-container no-element container-fluid-right-col-in-mw-layout-container no-element container">
                        <div class="ps-lg-5">
                            <div class="regular-mode">
                                <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h2>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}">It is more likely that it will have to wait till the end of George Bushes term in office and then get an amendment to the law that reverses only gambling ban part of the law</p>
                                <br/>
                            </div>
                            <x-row class="py-4 text-center text-sm-start d-flex justify-content-center justify-content-lg-between">
                                <div class="col-sm-12 col-md-10 mb-3 cloneable element background-color-element safe-mode d-block d-sm-flex align-items-start h-100">
                                    <i class="safe-element no-typing mw-micon-Alien-2 me-4 mt-2" style="font-size: 50px;"></i>
                                    <div class="regular-mode">
                                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h5>
                                        <p data-mwplaceholder="{{ _e('Enter text here') }}">If you’re looking for the latest in wireless headphones for your enjoyment</p>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-10 mb-3 cloneable element background-color-element safe-mode d-block d-sm-flex align-items-start h-100">
                                    <i class="safe-element no-typing mw-micon-Alien me-4 mt-2" style="font-size: 50px;"></i>
                                    <div class="regular-mode">
                                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h5>
                                        <p data-mwplaceholder="{{ _e('Enter text here') }}">If you’re looking for the latest in wireless headphones for your enjoyment</p>
                                    </div>
                                </div>
                            </x-row>

                            <module type="btn" button_style="btn-outline-primary" button_size="btn-md px-5" text="Learn More"/>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

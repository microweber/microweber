{{--
type: layout
name: Gallery 4
position: 4
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    :has-background="false"
    container-class="mw-layout-container no-element"
>
    <div class="mw-layout-container no-element container edit" id="layout-container{{ $params['id'] }}" field="layout-gallery-skin-4-{{ $params['id'] }}" rel="module">
            <x-row class="text-center">
                <div class="mx-auto col-sm-10 col-md-4 cloneable mb-md-5 mb-0 py-md-2 py-md-0 element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                    </div>
                    <div class="py-md-4 py-2 mt-md-auto safe-element">
                        <h5 class="mb-2">Image Title</h5>
                        <p>A short description of the image.</p>
                    </div>
                </div>

                <div class="mx-auto col-sm-10 col-md-4 cloneable mb-md-5 mb-0 py-md-2 py-md-0 element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                    </div>
                    <div class="py-md-4 py-2 mt-md-auto safe-element">
                        <h5 class="mb-2">Image Title</h5>
                        <p>A short description of the image.</p>
                    </div>
                </div>

                <div class="mx-auto col-sm-10 col-md-4 cloneable mb-md-5 mb-0 py-md-2 py-md-0 element">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                    </div>
                    <div class="py-md-4 py-2 mt-md-auto safe-element">
                        <h5 class="mb-2">Image Title</h5>
                        <p>A short description of the image.</p>
                    </div>
                </div>
            </x-row>
        </div>
</x-layout-section>

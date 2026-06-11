{{--
type: layout

name: Content 28

position: 28

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-28"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <x-row class="safe-mode">
                <div class="col-12 col-sm-10 col-lg-8 mx-auto pb-5 text-center d-flex align-items-center ">
                    <div class="regular-mode allow-select allow-drop">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4 font-weight-normal">Your Title Here</h5>
                        <h3 data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-5">The Awesome Mountain</h3>
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-12.jpg') }}" alt=""/>
                        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top-image-1" />
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Remember, your story is a dynamic tool that can evolve and adapt as your venture progresses. The way you tell your story online can indeed make a significant difference in building connections, generating interest, and achieving your goals.</p>
                        <module type="btn" text="Learn More" button_style="btn-primary" button_size=""/>
                    </div>


                </div>
            </x-row>
</x-layout-section>

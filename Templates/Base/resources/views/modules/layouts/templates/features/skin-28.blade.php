{{--
 type: layout
 name: Feature 28
 position: 28
 categories: Features
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-feature-skin-28"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center">
                <div class="col-12 mx-auto regular-mode">
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}">The Features Title</h2>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning of the original <br> Star Trek show really did do a good job of capturing our feelings about space.</p>
                    <br/>
                    <module type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" height="700">
                </div>
            </x-row>
</x-layout-section>

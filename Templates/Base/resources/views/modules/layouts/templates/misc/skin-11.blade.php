{{--
type: layout

name: Misc 11

position: 11

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-11"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-sm-8 mx-auto pb-5 text-center">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">The Glossary Of Telescopes</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">I can change any and everything in my life by simply changing myself. This puts me in the driving seat of my life and makes me responsible for my own journey.</p>
                </div>
                <div class="col-12 d-flex justify-content-center text-center pb-5">
                    <module type="tabs" template="new" default_content="1" class="tabs" />
                </div>
            </x-row>
</x-layout-section>

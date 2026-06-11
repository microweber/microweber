{{--
type: layout

name: Misc 3

position: 3

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-3"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-12">
                    <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold text-center">The amazing hubble</h5>
                    <h1 data-mwplaceholder="{{ _e('Enter text here') }}" class="text-center">To appreciate what is really exciting about radio astronomy, first we have to shift how we view astronomy.</h1>
                    <div class="col-md-8 mx-auto mt-5">
                        <module type="accordion" template="skin-2"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>

{{--
type: layout

name: Misc 14

position: 14

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-14"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-md-10 mx-auto">
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Frequently asked questions</h2>
                    <module type="accordion" template="default"/>
                </div>
            </x-row>
</x-layout-section>

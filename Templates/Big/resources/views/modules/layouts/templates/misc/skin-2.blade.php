{{--
type: layout

name: Misc 2

position: 2

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-2"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-12">
                    <module type="accordion" template="skin-1" />
                </div>
            </x-row>
</x-layout-section>

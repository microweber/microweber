{{--
type: layout

name: Misc 16

position: 16

categories: Misc
--}}

<style>
    .text-info {
        color: var(--mw-primary-color);
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section misc-16"
    field-name="layout-misc-skin-16"
    container-class="mw-layout-container no-element container edit my-5"
>
    <x-row>
                <div class="col-lg-12 col-12">
                    <h2 class="mb-5 text-center">Next <u class="text-info">Schedules</u></h2>
                    <module type="tabs" template="skin-2"/>
                </div>
            </x-row>
</x-layout-section>

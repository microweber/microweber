{{--
type: layout

name: Titles 2

position: 2

categories: Titles
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-titles-skin-2"
    container-class="mw-layout-container no-element container safe-mode edit safe-mode"
>
    <x-row class="text-center mb-5">
                <div class="col-lg-12 mx-auto regular-mode">
                    <h2 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-3">Jump to the Top</h2>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                </div>
            </x-row>
</x-layout-section>

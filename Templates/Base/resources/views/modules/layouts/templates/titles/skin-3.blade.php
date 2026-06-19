{{--
type: layout

name: Titles 3

position: 3

categories: Titles
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-titles-skin-3"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row class="text-center mb-5">
                <div class="col-lg-8 mx-auto regular-mode">
                    <h3 data-mwplaceholder="{{ __('Enter title here') }}">The future is here and belongs to you. Every dreamer is important for the universe</h3>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                </div>
            </x-row>
</x-layout-section>

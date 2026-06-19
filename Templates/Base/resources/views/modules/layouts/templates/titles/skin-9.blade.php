{{--
type: layout

name: Titles 9

position: 9

categories: Titles
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-titles-skin-9"
    container-class="mw-layout-container no-element container safe-mode edit safe-mode"
>
    <x-row class="mb-5">
                <div class="col-lg-12 mx-auto regular-mode">
                    <h5 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-3">Jump to the Top</h5>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                    <module type="btn" button_style="btn-link" text="Read More" />
                </div>
            </x-row>
</x-layout-section>

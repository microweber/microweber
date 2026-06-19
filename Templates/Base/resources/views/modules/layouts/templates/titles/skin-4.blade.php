{{--
type: layout

name: Titles 4

position: 4

categories: Titles
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-titles-skin-4"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row class="text-center mb-5">
                <div class="col-lg-10 mx-auto text-left regular-mode">
                    <h4 data-mwplaceholder="{{ __('Enter title here') }}">A memory warm and happy as a bird flew to me. <br>
                        Remind me of you and brighten my day.
                    </h4>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                </div>
            </x-row>
</x-layout-section>

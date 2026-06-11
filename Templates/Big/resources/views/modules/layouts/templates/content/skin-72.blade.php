{{--
type: layout

name: Content 72

position: 72

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section section-silver"
    field-name="layout-content-skin-72"
    default-padding-top="mw-p-t-100"
    default-padding-bottom="mw-p-b-50"
    container-class="mw-layout-container edit"
>
    <div class="col-12 col-md-8 mx-auto   text-center mb-md-5   regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title Here</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" style="text-align-last: center; text-align: justify !important;">So we will freeze completely, until Amina, in this unfortunate, ridiculous fate.</p>
            </div>

            <x-row class="safe-mode">
                <div class="col-12">
                    <module type="slider"/>
                </div>
            </x-row>
</x-layout-section>

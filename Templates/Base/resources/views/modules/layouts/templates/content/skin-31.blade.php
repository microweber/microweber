{{--
type: layout

name: Content 31

position: 31

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-31"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <x-row class="text-center mb-5">
                <div class="regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Your Awesome Title Here</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Audio player software is used to play back sound recordings in one of the many formats available for computers today</p>
                    <br/>
                    <module type="btn" button_style="btn-primary" text="Learn More"/>
                </div>
            </x-row>
</x-layout-section>

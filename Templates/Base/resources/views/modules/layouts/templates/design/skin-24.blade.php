{{--
type: layout
name: Design 24
position: 124
categories: Design
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-24"
    field-name="layout-new-layouts-skin-23"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="col-12">
                    <div class="mw-new-24-title-holder mb-4">
                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-24-title">Your Title Here</h2>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-24-subtitle">Your subtitle here</p>
                    </div>

                    <module type="posts" template="skin-22"/>
                </div>
            </x-row>
</x-layout-section>

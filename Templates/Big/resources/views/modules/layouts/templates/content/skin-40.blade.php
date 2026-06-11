{{--
type: layout

name: Content 40

position: 40

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-40"
    container-class="mw-layout-container container-fluid safe-mode no-element edit safe-mode"
>
    <x-row>
                <div class="col-12 col-md-6 mx-auto pb-5 mb-4 pe-lg-5 text-center text-lg-start d-flex align-items-center order-2 order-lg-1">
                    <div class=" text-center ">
                        <div class=" regular-mode  allow-drop allow-select">

                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Your Title Here</h3>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">To ensure the blackest blacks and sharpest colors on every print job, the Eclipse OEM-compatible toner cartridges use just premium</p>

                            <module type="btn" button_style="btn-primary" button_size="btn-md px-5" text="BUTTON"/>



                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="d-block mb-4">Follow us</p>

                            <module type="social_links" />
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 order-1 order-lg-2 mb-4 allow-drop allow-select">
                    <module type="slider"/>
                </div>
            </x-row>
</x-layout-section>

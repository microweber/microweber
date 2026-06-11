{{--
type: layout

name: Content 27

position: 27

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-27"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <x-row class="text-center safe-mode nodrop no-select">
                <div class="mx-auto col-sm-10 col-md-6 mb-5 cloneable element safe-mode background-color-element allow-select">
                    <div class="d-flex flex-column  allow-drop">

                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt=""/>

                        <div class="  pt-7 pb-6 px-5 mt-md-auto mt-5 regular-mode">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">The Awesome Mountain</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">From this moment on, choose not to let your past dictate your future. What is gone is gone – forever.</p>
                            <br />
                            <module type="btn" text="Button" button_style="btn-primary"/>
                        </div>
                    </div>
                </div>

                <div class="mx-auto col-sm-10 col-md-6 mb-5 cloneable element safe-mode background-color-element allow-select">
                    <div class="d-flex flex-column    allow-drop">

                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-9.jpg') }}" alt=""/>

                        <div class="  pt-7 pb-6 px-5 mt-md-auto mt-5 regular-mode">
                            <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">The Awesome Mountain</h3>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">For any aspect of self-improvement, there has to be a reason for you to start and keep going. </p>
                            <br />
                            <module type="btn" text="Button" button_style="btn-primary"/>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

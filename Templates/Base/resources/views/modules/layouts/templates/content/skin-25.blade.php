{{--
type: layout

name: Content 25

position: 25

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-25"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <x-row>
                <div class="col-12 col-sm-10 col-lg-6 mx-auto text-lg-start d-flex align-items-center order-2 order-lg-1">
                    <x-row class="mb-3 py-4 safe-mode">
                        <div class="col-lg-6 col-12 border-start border-left-3 border-dark pt-2 d-flex flex-column cloneable element background-color-element element regular-mode  allow-select allow-drop">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title</h5>
                            <br>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Did you know that you should try not to sneeze too strongly. Why? A very powerful sneeze has the ability to cause a fracture in your ribcage. </p>
                        </div>

                        <div class="col-lg-6 col-12 border-start border-left-3 border-dark pt-2 d-flex flex-column cloneable element background-color-element element regular-mode   allow-select allow-drop">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title</h5>
                            <br>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Over 92% of computers are infected with Adware and spyware. Such software is rarely accompanied by uninstall utility and even when.</p>
                        </div>
                    </x-row>
                </div>

                <div class="col-12 col-sm-10 col-lg-6 mx-auto order-1 order-lg-2 safe-mode">
                    <div class="  pb-5    allow-select allow-drop" style="min-height:50px">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}" alt=""/>
                    </div>
                </div>
            </x-row>
</x-layout-section>

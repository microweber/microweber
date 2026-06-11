{{--
type: layout

name: Content 10

position: 10

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-container safe-mode layout-content-skin-10-{{ $params['id'] }}-section"
    field-name="layout-content-skin-10"
    container-class="mw-layout-container container safe-mode noelement edit"
>
    <x-row class="mb-3 py-4">
                <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-5 cloneable element background-layout-element allow-drop allow-select  ">

                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>

                    <div class="  pt-4 pb-6 mt-md-auto mt-5 regular-mode layout-content">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">The summer forest</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create slimmer</p>
                    </div>

                </div>

                <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-5 cloneable element background-layout-element  allow-drop allow-select">
                    <div class="">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                    </div>
                    <div class="  pt-4 pb-6 mt-md-auto mt-5 regular-mode layout-content">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Mountain castle</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create slimmer</p>
                    </div>
                </div>

                <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-5 cloneable element background-layout-element  allow-drop allow-select">
                    <div class="">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                    </div>
                    <div class="  pt-4 pb-6 mt-md-auto mt-5 regular-mode layout-content">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">The old tree</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">LCD screens are uniquely modern in style, and the liquid crystals that make them work have allowed humanity to create slimmer</p>
                    </div>
                </div>
            </x-row>
</x-layout-section>

{{--
type: layout

name: Content 61

position: 61

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    container-class="mw-layout-container no-element"
>
    <div class="container-fluid px-0 mw-layout-container safe-mode no-element    edit  no-element" field="layout-content-skin-61-{{ $params['id'] }}" rel="module" style="min-height: 600px!important;">
            <x-row class="position-relative">
                <div class="col-xl-5 col-lg-6   position-lg-absolute px-0" style="left: 5%; top: 20%; z-index: 1; min-height: 400px!important;">
                    <div class="regular-mode mh-400 p-5 element no-drag background-color-element allow-select allow-drop" style="background-color: #000;">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" style="color: #fff;">Become a Explorer</h4>
                        <p data-mwplaceholder="{{ _e('Enter title here') }}" style="color: #fff;">It is a long established fact that a reader will be distracted by
                            <br> the readable content of a page when looking at its layout.
                        </p>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-7 mx-auto allow-select allow-drop">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-16.jpg') }}" alt="" class="img-cover mw-100" style="min-height: 600px;">
                </div>
            </x-row>
        </div>
</x-layout-section>

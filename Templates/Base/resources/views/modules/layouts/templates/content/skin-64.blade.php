{{--
type: layout

name: Content 64

position: 64

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="py-0 mw-layout-container safe-mode"
    field-name="layout-content-skin-64"
    default-padding-top="mw-p-t-70"
    default-padding-bottom="mw-p-b-40"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <div class="col-lg-10 mx-auto   regular-mode allow-drop allow-select">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Design Concept</h3>
                <p class="pb-md-4" data-mwplaceholder="{{ _e('Enter text here') }}">It is a long established fact that a reader will be distracted by
                    <br> the readable content of a page when looking at its layout.</p>
            </div>

            <x-row class="nodrop no-select">
                <div class="col-12 d-flex flex-wrap  element background-color-element safe-mode pb-md-3 pt-md-3   allow-select">
                    <div class=" col-lg-6  element allow-drop " style="min-height: 100%;">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt="" />
                    </div>

                    <div class="col-12  col-lg-6  element px-0  d-flex align-items-center      ">
                        <div class="  p-md-5 regular-mode allow-drop w-100 allow-select" style="min-height: 100%;">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">How To Meet That Special Someone</h5>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">There is no better advertisement campaign that is low cost and also successful at the same time. Great business ideas when utilized effectively can save lots of money. This is not only easy for those who work full-time as an advertiser, but also for those.</p>
                        </div>
                    </div>
                </div>

                <div class="col-12   d-flex flex-wrap  element background-color-element safe-mode  pb-md-3 pt-md-3   allow-select">
                    <div class=" col-lg-6   element allow-drop " style="min-height: 100%;">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt=""/>
                    </div>

                    <div class="col-12  col-lg-6 element px-0  d-flex align-items-center allow-select">
                        <div class="  p-md-5 regular-mode w-100 allow-drop" style="min-height: 100%;">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">How To Meet That Special Someone</h5>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">There is no better advertisement campaign that is low cost and also successful at the same time. Great business ideas when utilized effectively can save lots of money. This is not only easy for those who work full-time as an advertiser, but also for those.</p>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

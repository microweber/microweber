{{--
type: layout
name: Header 32
position: 32
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section section-7 d-flex"
    field-name="layout-header-skin-32"
    default-padding-top="mw-p-t-100"
    default-padding-bottom="mw-p-b-0"
    container-class="mw-layout-container container d-flex edit safe-mode"
>
    <x-row>
                <div class="col-lg-6 col-lg-6 allow-drop align-self-center allow-select">
                    <h4 data-mwplaceholder="@lang('Enter title here')" class="header-section-title">
                        Cosmetic beauty Clinic "Darlla"
                    </h4>

                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mt-3">
                        Beauty is our mision and we will help you achieve the desired
                    </p>
                    <div class="mt-5">
                        <module type="btn" class="allow-drop" button_text="Book a Consultation"/>
                    </div>
                </div>

                <div class="col-lg-6 col-lg-6 text-center allow-drop align-self-end allow-select">
                    <img loading="lazy" src="{{ asset('templates/big/img/sections/home.jpg') }}" alt=""/>


                </div>
            </x-row>
</x-layout-section>

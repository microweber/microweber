{{--
type: layout

name: Content 74

position: 74

categories: Content
--}}

<style>
    .tony-template-content-1-boxes {
        border: 1px solid black;
        padding: 20px 0px;
        margin: 20px 10px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        border-radius: 1px;
    }

    .tony-template-content-1-boxes:hover {
        border: unset;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        transform: scale(1.2);
    }

    .home-1-content-right {
        background-color: #ffffff;
        padding: 40px 0px 30px 0px;
        position: relative;
        bottom: 15px;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        border-radius: 5px;
    }

    .tony-p-gray-style {
        color: #7E8495;
        font-size: 20px;
    }

    .home-1-content-span-label {
        background-color: #1E2432;
        padding: 8px 20px;
        color: white;
        font-size: 12px;
        font-weight: 300;
    }

    .home-1-content-span-label-2 {
        background-color: #F5F7FD;
        padding: 8px 30px 8px 10px;
        color: #1E2432;
        font-size: 14px;
        font-weight: 400;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section {{ $layout_classes ?? '' }}"
    field-name="layout-content-skin-74"
    container-class="mw-layout-container container-fluid safe-mode no-element safe-mode edit"
>
    <x-row class="col-12 d-xl-flex mx-auto cloneable element safe-mode background-color-element safe-mode">

                <div class="col-12 col-xl-5 mx-auto pt-5 text-center text-lg-start d-flex align-item">
                    <div class="regular-mode">
                        <h2 class="pb-7">Creative Ideas, <br>
                            What we are Adorning</h2>

                        <p class="pb-4">We are a specialist agency in manufacturing design for <br> personal and corporate brands</p>

                        <module type="btn" button_style="btn-primary" text="Get Started"/>


                    </div>
                </div>

                <div class="col-12 col-xl-7  safe-mode cloneable element background-color-element safe-mode d-flex align-items-center h-100" style="background-color: #000; min-height: 700px;">
                    <div class="col-xl-6 col-lg-8 col-md-10 col-12 mx-auto mt-md-0 mt-10 d-md-flex justify-content-center cloneable element background-color-element safe-mode">
                        <div class="col-md-5 col-12 regular-mode">
                            <div class="tony-template-content-1-boxes cloneable element background-color-element d-flex flex-column justify-content-center align-items-center safe-mode" style="background-color: #ffffff4b;">
                                <i class="mw-micon-Add-Basket safe-element" style="font-size: 50px;"></i>
                                <p class="mt-2 mb-0" style="font-size: 14px;">Dress Well</p>
                            </div>

                        </div>
                        <div class="col-md-5 col-12 regular-mode">
                            <div class=" tony-template-content-1-boxes cloneable element background-color-element d-flex flex-column justify-content-center align-items-center safe-mode" style="background-color: #ffffff4b;">
                                <i class="mw-micon-Bio-Hazard safe-element" style="font-size: 50px;"></i>
                                <p class="mt-2 mb-0" style="font-size: 14px;">Meditate</p>
                            </div>
                        </div>
                    </div>

                </div>

            </x-row>
</x-layout-section>

@php
/*

type: layout

name: Price Lists 8

position: 8

categories: Price Lists

*/
@endphp

<style>
    .tony-price-list-column {
        border: 1px solid #DCE2E7;
        margin: 0 15px;
        border-radius: 5px;
        transition: all 0.4s ease-in-out;
    }

    .tony-price-list-column .card {
        border: unset;
    }

    .tony-price-list-column:hover {
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        border: none;
        transform: scale(1.05);
    }

    .tony-price-list-column:hover .card-header background-color-element element {
        background-color: #F5F7FD;
        border-radius: 0;
    }

    .tony-gray {
        color: #7E8495;
        font-size: 17px;
    }

    .tony-price-list-after-message {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 1px;
    }
</style>

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'p-t-70';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'p-b-70';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="{{ $layout_classes }} section ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit safe-mode" field="layout-tony-skin-8-{{ $params['id'] }}" rel="module">
        <div class="row mx-auto justify-content-center">
            <div class="col-xl-10">
                <h2 class="text-center allow-select regular-mode">Simple plans hosted in the cloud</h2>
                <div class="d-flex flex-wrap mx-auto justify-content-center allow-select">
                    <div class="tony-price-list-column background-color-element element col-xl-3 col-lg-6 col-md-8 col-12 my-5 px-0 cloneable py-0 element safe-mode">
                        <div class="card h-100 regular-mode">
                            <div class="card-header background-color-element element px-0">
                                <h6 class="py-3 text-center">Free</h6>
                            </div>
                            <div class="card-body my-3 mx-1 text-center">
                                <span class="h2">USD 0</span>
                                <br>
                                <div class="col text-center py-4">
                                    <div class="element">
                                        <p class="tony-gray mb-0">per user/month</p>
                                        <small class="tony-gray" style="font-size: 15px;">2 users</small>
                                    </div>
                                </div>
                                <div class="text-center my-3">
                                    <module type="btn" button_style="btn-outline-dark" button_text="Get Started" />
                                </div>
                                <div class="col text-center pt-2 my-3">
                                    <div class="element">
                                        <p class="my-2 tony-gray">Free forever</p>
                                        <p class="my-2 tony-gray">No obligation</p>
                                        <p class="my-2 tony-gray"><a href="" style="color: #1A4AB9;">Learn more</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tony-price-list-column background-color-element element col-xl-3 col-lg-6 col-md-8 col-12 my-5 px-0 cloneable py-0 element safe-mode">
                        <div class="card h-100 regular-mode">
                            <div class="card-header background-color-element element">
                                <h6 class="py-3 text-center">Standard</h6>
                            </div>
                            <div class="card-body my-3 mx-1 text-center">
                                <span class="h2">USD 7</span>
                                <br>
                                <div class="col text-center py-4">
                                    <div class="element">
                                        <p class="tony-gray mb-0">per user/month</p>
                                        <small class="tony-gray" style="font-size: 15px;">1-5 users</small>
                                    </div>
                                </div>
                                <div class="text-center my-3">
                                    <module type="btn" button_style="btn-outline-dark" button_text="Go Pro" />
                                </div>
                                <div class="col text-center pt-2 my-3">
                                    <div class="element">
                                        <p class="my-2 tony-gray">Free for 7 days</p>
                                        <p class="my-2 tony-gray">No credit card needed</p>
                                        <p class="my-2 tony-gray"><a href="" style="color: #1A4AB9;">Learn more</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tony-price-list-column background-color-element element col-xl-3 col-lg-6 col-md-8 col-12 my-5 px-0 cloneable py-0 element safe-mode">
                        <div class="card h-100 regular-mode">
                            <div class="card-header background-color-element element">
                                <h6 class="py-3 text-center">Premium</h6>
                            </div>
                            <div class="card-body my-3 mx-1 text-center">
                                <span class="h2">USD 14</span>
                                <br>
                                <div class="col text-center py-4">
                                    <div class="element">
                                        <p class="tony-gray mb-0">per user/month</p>
                                        <small class="tony-gray" style="font-size: 15px;">up to 2 users</small>
                                    </div>
                                </div>
                                <div class="text-center my-3">
                                    <module type="btn" button_style="btn-outline-dark" button_text="Start trial" />
                                </div>
                                <div class="col text-center pt-2 my-3">
                                    <div class="element">
                                        <p class="my-2 tony-gray">Free for 7 days</p>
                                        <p class="my-2 tony-gray">No credit card needed</p>
                                        <p class="my-2 tony-gray"><a href="" style="color: #1A4AB9;">Learn more</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 text-center mx-auto pt-4 pb-2 tony-price-list-after-message allow-select regular-mode">
                    <p><span style="font-weight: bold;">101+ users ?</span> We offer discounts for large teams! <a href="" style="color: #1A4AB9;">Calculate your price</a></p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

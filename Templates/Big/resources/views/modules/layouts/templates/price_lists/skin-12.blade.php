@php
/*

type: layout

name: Price Lists 12

position: 12

categories: Price Lists

*/
@endphp

@if (!isset($classes['padding_top']))
    @php $classes['padding_top'] = ''; @endphp
@endif
@if (!isset($classes['padding_bottom']))
    @php $classes['padding_bottom'] = ''; @endphp
@endif

@php
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .price-list-12 .price-list-12-section-heading {
        margin-bottom: 100px;
    }

    .price-list-12 .price-list-12-item {
        background-color: #f1f0fe;
        border-radius: 25px;
        position: relative;
    }

    .price-list-12 .price-list-12-item .price-list-12-image {
        position: relative;
    }

    .price-list-12 .price-list-12-item .price-list-12-image img {
        border-radius: 25px;
        max-height: 200px;
        width: 100% !important;
    }

    .price-list-12 .price-list-12-item .mwp-price-12-list-element span.category {
        font-size: 14px;
        text-transform: uppercase;
        color: #7a6ad8;
        background-color: #fff;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 20px;
    }

    .price-list-12 .price-list-12-item .mwp-price-12-list-element h4 {
        font-size: 22px;
        font-weight: 600;
    }

    .price-list-12 .price-list-12-item .mwp-price-12-list-element span {
        display: inline-block;
        font-size: 14px;
        color: #4a4a4a;
        margin-bottom: 10px;
    }

    .price-list-12 .price-list-12-item .mwp-price-12-list-element h6 {
        font-size: 16px;
        color: #7a6ad8;
        font-weight: 600;
    }

    .price-list-12 .price-list-12-item .price-list-12-right-bubble {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translate(0, -50%);
        background-color: #7a6ad8;
        width: 60px;
        height: 120px;
        z-index: 1;
        border-radius: 60px 0px 0px 60px;
        display: flex;
        align-items: center;
        justify-content: center;

        i:before {
            font-size: 24px;
            color: #fff;
        }
    }
</style>

<section class="section price-list-12 {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container allow-drop edit safe-mode" field="layout-skin-12-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="price-list-12-section-heading regular-mode">
                    <h6>Schedule</h6>
                    <h2>Upcoming Events</h2>
                </div>
            </div>
            <div class="col-lg-12 col-md-6 cloneable element mb-4">
                <div class="price-list-12-item background-color-element element p-lg-5 p-3">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="price-list-12-image element">
                                <img class="element" loading="lazy" alt="" src="{{ asset('templates/big/img/layouts/features-1-2-1.jpg') }}">
                            </div>
                        </div>
                        <div class="col-lg-9 row align-items-center mt-lg-0 mt-3">
                            <div class="mwp-price-12-list-element col-lg-4 col-12">
                                <span class="category background-color-element element">Web Design</span>
                                <h4>UI Best Practices</h4>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Date:</span>
                                <h6>16 Feb 2036</h6>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Duration:</span>
                                <h6>22 Hours</h6>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Price:</span>
                                <h6>$120</h6>
                            </div>
                            <a class="price-list-12-right-bubble background-color-element element">
                                <i class="mdi mdi-chevron-right icon no-typing"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-6 cloneable element mb-4">
                <div class="price-list-12-item background-color-element element p-lg-5 p-3">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="price-list-12-image element">
                                <img class="element" loading="lazy" alt="" src="{{ asset('templates/big/img/layouts/features-1-2-4.jpg') }}">
                            </div>
                        </div>
                        <div class="col-lg-9 row align-items-center mt-lg-0 mt-3">
                            <div class="mwp-price-12-list-element col-lg-4 col-12">
                                <span class="category background-color-element element">Web Design</span>
                                <h4>New Design Trend</h4>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Date:</span>
                                <h6>24 Feb 2036</h6>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Duration:</span>
                                <h6>30 Hours</h6>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Price:</span>
                                <h6>$320</h6>
                            </div>
                            <a class="price-list-12-right-bubble background-color-element element">
                                <i class="mdi mdi-chevron-right icon no-typing"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-6 cloneable element mb-4">
                <div class="price-list-12-item background-color-element element p-lg-5 p-3">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="price-list-12-image element">
                                <img class="element" loading="lazy" alt="" src="{{ asset('templates/big/img/layouts/features-1-2-3.jpg') }}">
                            </div>
                        </div>
                        <div class="col-lg-9 row align-items-center mt-lg-0 mt-3">
                            <div class="mwp-price-12-list-element col-lg-4 col-12">
                                <span class="category background-color-element element">Web Design</span>
                                <h4>Web Programming</h4>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Date:</span>
                                <h6>12 Mar 2036</h6>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Duration:</span>
                                <h6>48 Hours</h6>
                            </div>
                            <div class="mwp-price-12-list-element col">
                                <span>Price:</span>
                                <h6>$440</h6>
                            </div>
                            <a class="price-list-12-right-bubble background-color-element element">
                                <i class="mdi mdi-chevron-right icon no-typing"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

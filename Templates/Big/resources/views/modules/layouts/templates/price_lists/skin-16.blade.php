@php
    /*

    type: layout

    name: Price Lists 16

    position: 16

    categories: Price Lists

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = '';
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = '';
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-pricing9 {
        /* Bootstrap variables */
        --bs-body-color: #252d39;
        --bs-body-bg: rgb(255, 255, 255);

        /* Easy Frontend variables */
        --ezy-theme-color: var(--mw-primary-color);
        --ezy-theme-color-rgb: var(--mw-primary-color);
        --ezy-initial-color-rgb: #fff;
        --ezy-main-color-rgb: var(--mw-primary-color);
        --ezy-item-shadow: 0px 36px 94px rgba(33, 39, 62, 0.08);
        overflow: hidden;
    }


    /* Gray Block Style */
    .gray .mw-pricing9,
    .mw-pricing9.gray {
        /* Bootstrap variables */
        --bs-body-bg: rgb(239, 244, 253);

        /* Easy Frontend variables */
        --ezy-initial-color-rgb: 255, 255, 255;
    }

    /* Dark Gray Block Style */
    .dark-gray .mw-pricing9,
    .mw-pricing9.dark-gray {
        /* Bootstrap variables */
        --bs-body-color: #ffffff;
        --bs-body-bg: rgb(30, 39, 53);

        /* Easy Frontend variables */
        --ezy-initial-color-rgb: 11, 23, 39;
    }

    /* Dark Block Style */
    .dark .mw-pricing9,
    .mw-pricing9.dark {
        /* Bootstrap variables */
        --bs-body-color: #ffffff;
        --bs-body-bg: rgb(11, 23, 39);

        /* Easy Frontend variables */
        --ezy-initial-color-rgb: 30, 39, 53;
    }


    .mw-pricing9-btn {
        padding: 12px 30px;
        color: var(--ezy-theme-color-rgb);
        border: none;
    }

    .mw-pricing9-btn:not([class*="btn-outline"]) {
        background-color: var(--ezy-theme-color-rgb);
        color: #ffffff;
    }

    .mw-pricing9-btn.active,
    .mw-pricing9-btn:hover {
        background-color: var(--ezy-theme-color-rgb);
        color: #ffffff;
    }

    .mw-pricing9-tab-pane {
        color: var(--bs-body-color);
        animation: 1s ease 0s 1 normal none running fadeInBottom;
    }

    .mw-pricing9-tab-pane [class*="fa-"] {
        color: var(--ezy-theme-color-rgb);
    }

    .mw-pricing9-tab-pane,
    .mw-pricing9-nav-link {
        border: none;
        border-radius: 15px !important;
        background-color: var(--ezy-initial-color-rgb) !important;
        box-shadow: var(--ezy-item-shadow);
        transition: background-color 0.35s cubic-bezier(0.33, -0.11, 0.29, 1.35);
    }

    .mw-pricing9-nav-link {
        color: var(--bs-body-color) !important;
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    .mw-pricing9-nav-link.active::before {
        display: none;
        position: absolute;
        top: 50%;
        left: 100%;
        content: "";
        border-top: 35px solid transparent;
        border-bottom: 35px solid transparent;
        border-left: 35px solid var(--ezy-theme-color-rgb);
        transform: translate3d(0, -50%, 0);
        z-index: -1;
        animation: 0.25s ease 0s 1 normal none running fadeInRight;
    }

    @media (min-width: 768px) {
        .mw-pricing9-nav-link.active::before {
            display: block;
        }
    }

    .mw-pricing9-nav-link:hover {
        color: var(--ezy-theme-color-rgb) !important;
    }

    .mw-pricing9-nav-link.active {
        background-color: var(--ezy-theme-color-rgb) !important;
        color: #ffffff !important;
    }

    .mw-pricing9-nav-link [class*="fa-"] {
        font-size: 33px;
        display: none;
    }

    .mw-pricing9-nav-link:not(.active) .fa-circle {
        display: block;
        color: var(--ezy-theme-color-rgb);
    }

    .mw-pricing9-nav-link.active .fa-check-circle {
        display: block;
        animation: 1s ease 0s 1 normal none running fadeInBottom;
    }

    .pricing-9-price-span {
        font-size: 30px;
    }

    @keyframes fadeInBottom {
        0% {
            opacity: 0;
            transform: translate3d(0px, -10px, 0px);
        }

        100% {
            opacity: 1;
            transform: translate3d(0px, 0px, 0px);
        }
    }

    @keyframes fadeInRight {
        0% {
            transform: translate3d(-10px, -50%, 0);
        }

        100% {
            transform: translate3d(0, -50%, 0);
        }
    }
</style>

<section class="section mw-pricing9 price-list-16 {{ $layout_classes }}">

    <module type="background" id="background-layout--{{ $params['id'] }}"/>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container mw-layout-overlay-container edit safe-mode" field="layout-skin-16-{{ $params['id'] }}" rel="module">

        <div class="row mb-5 allow-select">
            <div class="col-lg-6 mb-lg-4">
                <h2 class="mw-pricing9-heading mb-0">Best Plan with more facilities and benefit</h2>
            </div>
        </div>
        <div class="row align-items-start allow-select">
            <div class="col-md-7">
                <div class="nav flex-column me-md-3" id="ezy-pricing9-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link d-flex mw-pricing9-nav-link active px-4 py-5 mb-3" id="ezy-pricing9-price1-tab" data-bs-toggle="pill" data-bs-target="#ezy-pricing9-price1" type="button" role="tab" aria-controls="ezy-pricing9-price1" aria-selected="true">
                        <div class="row align-items-center">
                            <span class="col-auto">
                                <span class="fa fa-circle"></span>
                                <span class="fa fa-check-circle"></span>
                            </span>
                            <span class="col text-start">
                                <h3 class="fw-bold mb-2 mw-pricing9-title">Basic</h3>
                                <p class="opacity-75 mb-0 mw-pricing9-note">It’s easier to reach your savings goals when you have the right savings account.</p>
                            </span>
                            <div class="col-auto">
                                <span class="mw-pricing9-price mb-3">
                                    <span class="pricing-9-price-span fw-bold">$9</span>
                                    <span class="ms-2 opacity-75">/month</span>
                                </span>
                            </div>
                        </div>
                    </button>
                    <button class="nav-link d-flex mw-pricing9-nav-link px-4 py-5 mb-3" id="ezy-pricing9-price2-tab" data-bs-toggle="pill" data-bs-target="#ezy-pricing9-price2" type="button" role="tab" aria-controls="ezy-pricing9-price2" aria-selected="true">
                        <div class="row align-items-center">
                            <span class="col-auto">
                                <span class="fa fa-circle"></span>
                                <span class="fa fa-check-circle"></span>
                            </span>
                            <span class="col text-start">
                                <h3 class="fw-bold mb-2 mw-pricing9-title">Standard</h3>
                                <p class="opacity-75 mb-0 mw-pricing9-note">It’s no secret that the digital industry is booming. From exciting startups to global brands.</p>
                            </span>
                            <div class="col-auto">
                                <span class="mw-pricing9-price mb-3">
                                    <span class="pricing-9-price-span fw-bold">$69</span>
                                    <span class="ms-2 opacity-75">/month</span>
                                </span>
                            </div>
                        </div>
                    </button>
                    <button class="nav-link d-flex mw-pricing9-nav-link px-4 py-5 mb-3" id="ezy-pricing9-price3-tab" data-bs-toggle="pill" data-bs-target="#ezy-pricing9-price3" type="button" role="tab" aria-controls="ezy-pricing9-price3" aria-selected="true">
                        <div class="row align-items-center">
                            <span class="col-auto">
                                <span class="fa fa-circle"></span>
                                <span class="fa fa-check-circle"></span>
                            </span>
                            <span class="col text-start">
                                <h3 class="fw-bold mb-2 mw-pricing9-title">Premium</h3>
                                <p class="opacity-75 mb-0 mw-pricing9-note">More off this less hello salamander lied porpoise much circa horse taped.</p>
                            </span>
                            <div class="col-auto">
                                <span class="mw-pricing9-price mb-3">
                                    <span class="pricing-9-price-span fw-bold">$99</span>
                                    <span class="ms-2 opacity-75">/month</span>
                                </span>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div class="col-md-5">
                <div class="tab-content" id="ezy-pricing9-tabContent">
                    <div class="tab-pane mw-pricing9-tab-pane p-3 p-lg-5 fade show active" id="ezy-pricing9-price1" role="tabpanel" aria-labelledby="ezy-pricing9-price1-tab">
                        <ul class="nav flex-column">
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">24/7 Contact support</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Tools easily</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">24/7 Contact support</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Tools easily</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li>
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">6TB storage</span>
                            </li>
                        </ul>
                        <module type="btn" button_text="Choose plan" button_style="btn btn-primary w-100 mt-4"/>
                    </div>
                    <div class="tab-pane mw-pricing9-tab-pane p-3 p-lg-5 fade" id="ezy-pricing9-price2" role="tabpanel" aria-labelledby="ezy-pricing9-price2-tab">
                        <ul class="nav flex-column">
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">24/7 Contact support</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Tools easily</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">24/7 Contact support</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Tools easily</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li>
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">6TB storage</span>
                            </li>
                        </ul>
                        <module type="btn" button_text="Choose plan" button_style="btn btn-primary w-100 mt-4"/>
                    </div>
                    <div class="tab-pane mw-pricing9-tab-pane p-3 p-lg-5 fade" id="ezy-pricing9-price3" role="tabpanel" aria-labelledby="ezy-pricing9-price2-tab">
                        <ul class="nav flex-column">
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">24/7 Contact support</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Tools easily</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">24/7 Contact support</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Tools easily</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Build Links</span>
                            </li>
                            <li class="mb-3">
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">Over 66 complex</span>
                            </li>
                            <li>
                                <span class="fa fa-check me-2"></span>
                                <span class="opacity-75">6TB storage</span>
                            </li>
                        </ul>
                        <module type="btn" button_text="Choose plan" button_style="btn btn-primary w-100 mt-4"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

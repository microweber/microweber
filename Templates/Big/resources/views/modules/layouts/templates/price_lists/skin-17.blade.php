@php
    /*

    type: layout

    name: Price Lists 17

    position: 17

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
    .price-list-17 .container-fluid {
        padding: 0;
    }

    .price-list-17 .event-item {
        background-size: cover;
        background-position: center;
        min-height: 600px !important;
        padding: 30px;
        position: relative;
    }

    @media (max-width: 575px) {
        .price-list-17 .event-item {
            min-height: 500px;
        }
    }

    .price-list-17 .event-item h3 {
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
        position: relative;
    }

    .price-list-17 .event-item .price {
        color: #fff;
        border-bottom: 2px solid var(--mw-primary-color);
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
    }

    .price-list-17 .event-item .description {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.9);
        position: relative;
    }

    .price-list-17 .event-item:before {
        content: "";
        position: absolute;
        left: 0; right: 0;
        top: 0; bottom: 0;
        background: rgba(0,0,0,.6);
        pointer-events: none;
        z-index: 1;
    }
    .price-list-17-image{
        position: absolute;
        top:0;
        left: 0;
        object-fit: cover;
        width: 100% !important;
        height: 100% !important;

    }
    .price-list-17 .row .cloneable > :not(img) {
        z-index: 2;
        position: relative;
    }

</style>

<section class="section price-list-17 {{ $layout_classes }}">

    <module type="background" id="background-layout--{{ $params['id'] }}"/>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container-fluid mw-layout-container mw-layout-overlay-container edit safe-mode  " field="layout-skin-17-{{ $params['id'] }}" rel="module">

        <div class="section-header text-center mb-5 allow-drop allow-select">
            <p data-mwplaceholder="{{ __('Enter title here') }}">Events</p>
            <h2 data-mwplaceholder="{{ __('Enter title here') }}">Share <span>Your Moments</span> In Our Restaurant</h2>
        </div>

        <div class="row ">
            <div class="col-lg-4 cloneable element event-item  relative d-flex flex-column justify-content-end allow-drop allow-select"  >
                <img class="element no-resize price-list-17-image" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt="" loading="lazy">
                <h3>Custom Parties</h3>
                <h2 class="price align-self-start">$99</h2>
                <p class="description">
                    Quo corporis voluptas ea ad. Consectetur inventore sapiente ipsum voluptas eos omnis facere. Enim facilis veritatis id est rem repudiandae nulla expedita quas.
                </p>
            </div>

            <div class="col-lg-4 cloneable element relative event-item d-flex flex-column justify-content-end allow-drop allow-select">
                <img class="element no-resize price-list-17-image" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt="" loading="lazy">
                <h3>Private Parties</h3>
                <h2 class="price align-self-start">$289</h2>
                <p class="description">
                    In delectus sint qui et enim. Et ab repudiandae inventore quaerat doloribus. Facere nemo vero est ut dolores ea assumenda et. Delectus saepe accusamus aspernatur.
                </p>
            </div>

            <div class="col-lg-4 cloneable relative element event-item d-flex flex-column justify-content-end allow-drop allow-select">
                <img class="element no-resize price-list-17-image" src="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}" alt="" loading="lazy">
                <h3>Birthday Parties</h3>
                <h2 class="price align-self-start">$499</h2>
                <p class="description">
                    Laborum aperiam atque omnis minus omnis est qui assumenda quos. Quis id sit quibusdam. Esse quisquam ducimus officia ipsum ut quibusdam maxime. Non enim perspiciatis.
                </p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

{{--
 type: layout
 name: Feature 59
 position: 59
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-featured34 {
        /* Bootstrap variables */
        --bs-body-color: #23262f;
        --bs-body-bg: #fff;

        /* Easy Frontend variables */
        --ezy-theme-color: rgb(13, 110, 253);
        --ezy-theme-color-rgb: 13, 110, 253;
        --ezy-card-bg: #ffffff;
        --ezy-item-bg: #ffffff;
        --ezy-shape-color: var(--mw-primary-color);
        --ezy-card-shadow: 0 38px 75px rgba(186, 204, 220, 0.23);
        --ezy-item-shadow: 0 10px 75px rgba(186, 204, 220, 0.23);

        background-color: var(--bs-body-bg);
        overflow: hidden;
    }

    /* Gray Block Style */
    .gray .mw-featured34,
    .mw-featured34.gray {
        /* Bootstrap variables */
        --bs-body-bg: rgb(246, 246, 246);
    }

    /* Dark Gray Block Style */
    .dark-gray .mw-featured34,
    .mw-featured34.dark-gray {
        /* Bootstrap variables */
        --bs-body-color: #ffffff;
        --bs-body-bg: rgb(30, 39, 53);

        /* Easy Frontend variables */
        --ezy-card-bg: rgb(11, 23, 39);
        --ezy-item-bg: rgb(11, 23, 39);
        --ezy-shape-color: rgb(0, 0, 0);
        --ezy-card-shadow: 0 38px 75px rgba(18, 13, 13, 0.5);
        --ezy-item-shadow: 0 20px 34px rgba(18, 13, 13, 0.5);
    }

    /* Dark Block Style */
    .dark .mw-featured34,
    .mw-featured34.dark {
        /* Bootstrap variables */
        --bs-body-color: #ffffff;
        --bs-body-bg: rgb(11, 23, 39);

        /* Easy Frontend variables */
        --ezy-card-bg: rgb(30, 39, 53);
        --ezy-item-bg: rgb(30, 39, 53);
        --ezy-shape-color: rgb(0, 0, 0);
        --ezy-card-shadow: 0 38px 75px rgba(18, 13, 13, 0.5);
        --ezy-item-shadow: 0 20px 34px rgba(18, 13, 13, 0.5);
    }

    .mw-featured34-heading {
        font-weight: bold;
        color: #fff;
    }

    @media (min-width: 768px) {
        .mw-featured34-heading {
            margin-top: 70px;
        }
    }

    .mw-featured34-sub-heading {
        color: #fff;
        opacity: 0.8;
    }

    .mw-featured34-item {
        background-color: var(--ezy-item-bg);
        border-radius: 20px;
        box-shadow: var(--ezy-item-shadow);
    }

    .mw-featured34-banner {
        border-radius: 10px;
    }

    .mw-featured34-title {
        color: var(--bs-body-color);
    }

    .mw-featured34-content {
        color: var(--bs-body-color);
        opacity: 0.7;
    }

    .mw-featured34-shape {
        position: absolute;
        right: -100%;
        bottom: -200px;
        background-color: var(--ezy-shape-color);
        min-width: 250vw;
        height: 500px;
        top:0;
    }

    @media (min-width: 768px) {
        .mw-featured34-shape {
            right: 30%;
        }
    }

    .mw-featured34-wrapper {
        background-color: var(--ezy-card-bg);
        padding: 16px;
        position: relative;
        box-shadow: var(--ezy-card-shadow);
    }

    @media (min-width: 768px) {
        .mw-featured34-wrapper {
            padding: 60px;
        }
    }

    .mw-featured34 img {
        max-height: 300px;
        max-width: 100%;
        width: auto;
        object-fit: cover;
    }
</style>

<section class="section feature-59 mw-featured34 {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <div class="mw-layout-container container-fluid no-element edit" field="layout-feature-skin-59-{{ $params['id'] }}" rel="module">
        <div class="row mb-5 position-relative">
            <div class="mw-featured34-shape background-color-element element"></div>
            <div class="col-md-8 text-start position-relative">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-featured34-heading header-section-title mb-4">Our Features</h1>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-featured34-sub-heading mb-4">
                    Banks likewise put away cash to develop their hold of cash. What they do is directed by laws. Those laws vary in various nations.
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mw-featured34-wrapper background-color-element element">
                    <div class="row text-center">
                        <div class="col-md-6 mb-3 mb-md-4 cloneable element">
                            <div class="mw-featured34-item position-relative p-4 p-lg-5 element background-color-element">
                                <img loading="lazy" class="img-fluid mw-featured34-banner mb-4" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt="">
                                <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-featured34-title fw-bold mb-3">Product Design</h4>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-featured34-content mb-0">
                                    Assumenda non repellendus distinctio nihil dicta sapiente, quibusdam maiores, illum at, aliquid blanditiis eligendi qui.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-4 cloneable element">
                            <div class="mw-featured34-item position-relative p-4 p-lg-5 element background-color-element">
                                <img loading="lazy" class="img-fluid mw-featured34-banner mb-4" src="{{ asset('templates/big/img/layouts/gallery-1-12.jpg') }}" alt="">
                                <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-featured34-title fw-bold mb-3">Branding</h4>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-featured34-content mb-0">
                                    Man our from light they're cattle upon created female. You first land evening beast won't had bring first void meat.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-4 cloneable element">
                            <div class="mw-featured34-item position-relative p-4 p-lg-5 element background-color-element">
                                <img loading="lazy" class="img-fluid mw-featured34-banner mb-4" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt="">
                                <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-featured34-title fw-bold mb-3">Photography</h4>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-featured34-content mb-0">
                                    Bearing bearing form night spirit, for signs isn't, tree fourth i there two land deep man without seasons fill itself.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-4 cloneable element">
                            <div class="mw-featured34-item position-relative p-4 p-lg-5 element background-color-element">
                                <img loading="lazy" class="img-fluid mw-featured34-banner mb-4" src="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}" alt="">
                                <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-featured34-title fw-bold mb-3">Marketing</h4>
                                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-featured34-content mb-0">
                                    Moving seasons, tree you're creeping third behold may be. Whose living for moving female seas heaven unto winged.
                                </p>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <module type="btn" button_style="btn-primary" button_text="Read more"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

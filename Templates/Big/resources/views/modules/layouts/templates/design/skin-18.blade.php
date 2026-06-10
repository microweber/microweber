{{--
type: layout
name: Design 18
position: 118
categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    h2 {
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .margin-40px {
        margin-top: 40px;
    }

    .mw-new-18-max-w-center {
        margin-left: auto;
        margin-right: auto;
        max-width: 550px;
        text-align: center;
    }

    .margin-150px {
        margin-top: 150px;
    }

    .mw-new-18-gray-color {
        opacity: .4;
    }

    .mw-new-18-subhead-main {
        color: #000;
        font-size: 25px;
        font-weight: 600;
        line-height: 1.4;
        opacity: 1;
    }

    .mw-new-18-logo-strip-main {
        column-gap: 100px;
        display: flex;
        filter: invert();
        flex-wrap: wrap;
        justify-content: center;
        margin-left: auto;
        margin-right: auto;
        max-width: 500px;
        row-gap: 40px;
    }

    @media screen and (min-width: 1440px) {
        .mw-new-18-logo-strip-main {
            column-gap: 46px;
        }
    }

    @media screen and (max-width: 991px) {
        .mw-new-18-logo-strip-main {
            align-items: center;
            column-gap: 30px;
            justify-content: center;
            row-gap: 40px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-18-subhead-main {
            font-size: 28px;
        }

        .mw-new-18-logo-strip-main {
            column-gap: 50px;
            justify-content: center;
            margin-top: 15px;
            row-gap: 50px;
            width: 100%;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-18-subhead-main {
            font-size: 25px;
            max-width: 100%;
        }

        .mw-new-18-logo-strip-main {
            column-gap: 40px;
            justify-content: center;
            row-gap: 40px;
        }
    }

    .mw-new-18-logo-strip-main img {
        width: auto;
        max-height: 24px;
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-18">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-18-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="mw-new-18-max-w-center">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-18-subhead-main">We are grateful for the opportunity to work
                    <span class="mw-new-18-gray-color">alongside top-tier brands.</span>
                </h2>
            </div>

            <div class="mt-5">
                {{-- AI-92 / BIG2-E (cycle-98 2026-05-09): brand logos
                     are meaningful (the surrounding text says "We are
                     grateful for the opportunity to work alongside top-
                     tier brands" — the LOGOS ARE THE INFORMATION).
                     Empty alt would deny SR users the names of the
                     brands. Each logo gets a "<Brand> logo" alt. --}}
                <div class="mw-new-18-logo-strip-main">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/logo_amazon.png') }}" alt="Amazon logo"/>
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/logo_facebook.png') }}" alt="Facebook logo"/>
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/logo_google.png') }}" alt="Google logo"/>
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/logo_linkedin.png') }}" alt="LinkedIn logo"/>
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/logo_philips.png') }}" alt="Philips logo"/>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

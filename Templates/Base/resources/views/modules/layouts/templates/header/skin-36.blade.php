{{--
type: layout
name: Header 36
position: 36
categories: Header
--}}

<style>
    .mw-header-36-hero {
        position: relative;
        overflow: hidden;
        padding-top: 330px;
        padding-bottom: 330px;
    }

    @media screen and (min-width: 991px) {
        .mw-header-36-hero {
            height: 60vh;
        }
    }

    .mw-header-36-avatar-image-large-wrapper img {
        width: 90.4px !important;
        height: 90.4px !important;
        object-fit: cover;
        border-radius: 100px;
        max-width: 100%;
    }

    .mw-header-36-hero-title,
    .mw-header-36-hero h2 {
        background: #fff;
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
        border-radius: 100px;
        display: inline-block;
        padding: 8px 24px;
    }

    .mw-header-36-hero-title {
        font-size: 44px;
    }

    .mw-header-36-hero h2 {
        font-size: 38px;
    }

    .mw-header-36-hero-text {
        position: relative;
        z-index: 22;
        top: 70px;
    }

    .mw-header-36-hero-image-wrap {
        background: #fff;
        border-radius: 100%;
        width: 350px;
        height: 350px;
        position: absolute;
        z-index: 22;
        top: -50px;
        right: 0;
        left: 0;
        margin: auto;
        pointer-events: none;
    }

    .mw-header-36-hero-image {
        position: absolute;
        z-index: 22;
        top: 0;
        width: 100%;
        min-width: 550px;
    }

    .mw-header-36-hero svg {
        position: absolute;
        z-index: 2;
        bottom: 0;
        right: 0;
        left: 0;
        overflow: hidden;
        height: 100%;
        pointer-events: none;
    }

    @media screen and (max-width: 991px) {
        .mw-header-36-hero {
            padding-top: 200px;
            padding-bottom: 400px;
        }

        .mw-header-36-hero-text {
            top: 0;
            margin-bottom: 120px;
        }
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-header-36-hero d-flex justify-content-center align-items-center mw-header-section-mh-100vh"
    background-attrs='data-background-color="var(--mw-primary-color)"'
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <div class="container mw-layout-container" style="z-index: 3;">
            <x-row class="edit  no-element" field="layout-header-skin-36-{{ $params['id'] ?? '' }}" rel="module">
                <div class="col-lg-7 col-12 allow-select">
                    <div class="mw-header-36-hero-text safe-mode">
                        <div class="mw-header-36-hero-title-wrap d-flex align-items-center mb-4 regular-mode mw-header-36-avatar-image-large-wrapper">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/happy-bearded-young-man.jpg') }}" class="mw-header-36-avatar-image mw-header-36-avatar-image-large img-fluid" alt=""/>

                            <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title background-color-element element mw-header-36-hero-title ms-3 mb-0">Hello friend!</h1>
                        </div>

                        <div class="regular-mode">
                            <h2 data-mwplaceholder="@lang('Enter title here')" class="header-section-title background-color-element element mb-4">I'm available for freelance work.</h2>

                            <module type="btn" class="mb-4" button_style="btn-secondary" text="Let's begin"/>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-12 position-relative allow-select safe-mode">
                    <div class="mw-header-36-hero-image-wrap background-color-element element"></div>
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/portrait-happy-excited-man-holding-laptop-computer.png') }}" class="mw-header-36-hero-image img-fluid" alt=""/>
                </div>
            </x-row>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffcf57" fill-opacity="1" d="M0,160L24,160C48,160,96,160,144,138.7C192,117,240,75,288,64C336,53,384,75,432,106.7C480,139,528,181,576,208C624,235,672,245,720,240C768,235,816,213,864,186.7C912,160,960,128,1008,133.3C1056,139,1104,181,1152,202.7C1200,224,1248,224,1296,197.3C1344,171,1392,117,1416,90.7L1440,64L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z"></path></svg>
</x-layout-section>

{{--
type: layout
name: Design 11
position: 111
categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-new-layouts-11 {
        .mw-new-11-who-we-are-wrapper {
            align-items: center;
            display: flex;
            flex-direction: row;
            grid-auto-columns: 1fr;
            grid-template-columns: 1fr .75fr;
            grid-template-rows: auto;
            justify-content: flex-start;
        }

        .mw-new-11-photo-animation-flex {
            align-items: flex-start;
            column-gap: 48px;
            display: flex;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            max-width: 90%;
            position: relative;
        }

        .mw-new-11-title {
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 0;
            margin-top: 0;
            mix-blend-mode: soft-light;
        }

        @media screen and (min-width: 1200px) {
            .mw-new-11-title {
                font-size: 76px;
            }
        }

        .mw-new-11-text-wrapper {
            align-items: center;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .mw-new-11-photo-line-animation {
            border-radius: 25px;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        .mw-new-11-photo-line-animation.vertical img {
            min-height: 800px !important;
        }

        .mw-new-11-photo-line-animation.horizontal img {
            max-height: 450px !important;
        }

        .mw-new-11-photo {
            height: 100%;
            object-fit: cover;
            object-position: 50% 0%;
            width: 100vw;
        }

        .mw-new-11-heading-overlay {
            background-color: #fafafa;
            bottom: auto;
            height: 100%;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            z-index: 2;
        }

        .mw-new-11-subhead-main {
            color: #222;
            font-size: 26px;
            font-weight: 600;
            line-height: 1.4;
            opacity: 1;
        }

        .mw-new-11-gray-color {
            opacity: .4;
        }

        @media screen and (max-width: 991px) {
            .mw-new-11-photo-animation-flex {
                max-width: 100%;
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-11-photo-animation-flex {
                column-gap: 20px;
                flex-direction: column;
                row-gap: 30px;
            }

            .mw-new-11-title {
                font-size: 50px;
            }

            .mw-new-11-subhead-main {
                font-size: 28px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-11-photo-animation-flex {
                flex-direction: column-reverse;
                row-gap: 30px;
            }

            .mw-new-11-subhead-main {
                font-size: 25px;
            }
        }

        .mw-new-11-who-we-are-wrapper.mw-new-11-_2, p {
            margin-top: 0;
        }

        .mw-new-11-title.mw-new-11-absolute {
            bottom: 40px;
            left: auto;
            position: absolute;
            right: 0;
            top: auto;
            z-index: 2;
        }

        .mw-new-11-text-wrapper.mw-new-11-_1 {
            width: 100%;
        }

        .mw-new-11-text-wrapper.mw-new-11-_2 {
            width: 85%;
        }

        .mw-new-11-heading-overlay.mw-new-11-_2 {
            transform: translate(0%, 100%);
        }

        .mw-new-11-subhead-main.right {
            margin-left: auto;
            max-width: 370px;
        }

        @media screen and (max-width: 991px) {
            .mw-new-11-title.mw-new-11-absolute {
                bottom: -19px;
                right: 0;
            }

            .mw-new-11-subhead-main.right {
                max-width: 300px;
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-11-title.mw-new-11-absolute {
                position: relative;
            }

            .mw-new-11-text-wrapper.mw-new-11-_2 {
                width: 100%;
            }

            .mw-new-11-subhead-main.right {
                max-width: 100%;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-11-title.mw-new-11-absolute {
                position: relative;
                right: 0;
                top: 0;
            }

            .mw-new-11-text-wrapper.mw-new-11-_2 {
                width: 100%;
            }
        }
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-11">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="mw-new-11-who-we-are-wrapper mw-new-11-_2">
                <div class="mw-new-11-photo-animation-flex">
                    <div class="mw-new-11-text-wrapper mw-new-11-_1">
                        <div class="mw-new-11-photo-line-animation vertical">
                            <img loading="lazy" class="mw-new-11-overlapping-avatar" src="{{ asset('templates/big/img/layouts/gallery-1-vertical.jpg') }}" alt=""/>
                            <div class="mw-new-11-heading-overlay mw-new-11-_2"></div>
                        </div>
                    </div>
                    <div class="mw-new-11-text-wrapper mw-new-11-_2">
                        <div class="mw-new-11-photo-line-animation horizontal">
                            <img loading="lazy" class="mw-new-11-overlapping-avatar" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt=""/>
                            <div class="mw-new-11-heading-overlay mw-new-11-_2"></div>
                        </div>
                    </div>
                    <div class="mw-new-11-title mw-new-11-absolute">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-11-title">Who we are</h3>
                        <div class="mt-4">
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-11-subhead-main right">In the realm of CMS templates, <span class="mw-new-11-gray-color">CMS takes the lead with designs favored by users.</span><strong><br></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

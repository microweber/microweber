{{--
 type: layout
 name: Design 7
 position: 107
 categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-new-layouts-7 {
        a {
            font-weight: 400;
            text-decoration: none;
        }

        @media screen and (max-width: 479px) {
            .mw-new-7-inherited-styles-for-exported-element {
                font-size: 15px;
            }
        }

        .w-inline-block {
            max-width: 100%;
        }

        .mw-new-7-mw-new-7-container---main {
            margin-left: auto;
            margin-right: auto;
            max-width: 1414px;
            padding-left: 24px;
            padding-right: 24px;
            width: 100%;
        }

        .mw-new-7-large-text {
            font-size: 23px;
            letter-spacing: -.01em;
            line-height: 1.3em;
        }

        .small-text {
            font-size: 14px;
            line-height: 1.35em;
        }

        .mw-new-7-heading-one {
            color: #222;
            font-weight: 400;
            letter-spacing: -.02em;
            line-height: 1em;
        }

        .text-bold {
            color: #222;
            font-variation-settings: "wght" 600;
        }

        .mw-new-7-container---m {
            max-width: 671px;
            width: 100%;
        }

        .mw-new-7-rounded-image {
            border-radius: 10px;
            display: block;
        }

        .mw-new-7-container---s {
            max-width: 555px;
            width: 100%;
        }

        .customer-hero {
            align-items: center;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .review-box-item {
            column-gap: 12px;
            display: flex;
            row-gap: 12px;
            text-align: left;
        }

        .review-box-divider {
            align-self: stretch;
            background-color: rgba(34, 34, 34, .2);
            width: 1px;
        }

        .horizontal-buttons, .review-items {
            align-items: center;
            column-gap: 18px;
            display: flex;
            row-gap: 18px;
        }

        .mw-new-7-align-center {
            margin-left: auto;
            margin-right: auto;
        }

        .mw-new-7widget {
            background-color: #fff;
            border: 1px solid rgba(34, 34, 34, .1);
            border-image: none 100% 1 0 stretch;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, .09) 0 1px 4px;
        }

        .mw-new-7widget-body {
            column-gap: 18px;
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 18px;
            row-gap: 18px;
        }

        .hero-wrapper-2 {
            align-items: center;
            column-gap: 24px;
            display: grid;
            grid-auto-columns: 1fr;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto;
            justify-items: center;
            row-gap: 24px;
        }

        .mw-new-7-hero-composite {
            position: relative;
        }

        @media screen and (max-width: 991px) {
            .hero-wrapper-2 {
                column-gap: 96px;
                display: flex;
                flex-direction: column;
                row-gap: 96px;
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-7-large-text {
                font-size: 20px;
                line-height: 1.35em;
            }

            .review-box-divider {
                display: none;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-7-mw-new-7-container---main {
                padding-left: 18px;
                padding-right: 18px;
            }

            .mw-new-7-large-text, .small-text {
                font-size: 17px;
                line-height: 1.35em;
            }

            .small-text {
                font-size: 12px;
            }

            .mw-new-7-heading-one {
                line-height: 1.15em;
            }

            .mw-new-7-rounded-image {
                border-radius: 6px;
            }

            .customer-hero {
                align-items: stretch;
            }

            .review-box-divider {
                height: 1px;
                opacity: .7;
                width: 100%;
            }

            .horizontal-buttons, .review-items {
                align-self: stretch;
                flex-direction: column;
            }

            .horizontal-buttons {
                align-items: stretch;
            }

            .review-items {
                column-gap: 18px;
                row-gap: 18px;
            }

            .mw-new-7widget {
                border-radius: 6px;
            }

            .mw-new-7widget-body {
                padding: 16px;
            }

            .hero-wrapper-2 {
                column-gap: 48px;
                row-gap: 48px;
            }
        }
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-7">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" height="40px" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="mw-new-7-mw-new-7-container---main mw-new-7-inherited-styles-for-exported-element mw-layout-container no-element edit safe-mode no-typing"
         field="layout-new-layouts-skin-7-{{ $params['id'] }}" rel="module">

        <div class="hero-wrapper-2">
            <div class="mw-new-7-container---s text-center">
                <h2 class="mw-new-7-heading-one" data-mwplaceholder="{{ _e('Enter title here') }}">On-demand Legal</h2>
                <h2 class="mw-new-7-heading-one" data-mwplaceholder="{{ _e('Enter title here') }}" style="font-style: italic;">with CMS.</h2>

                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-7-large-text mb-5">Specialized legal guidance tailored for the needs of new and growing businesses.</p>

                <div class="horizontal-buttons d-flex align-items-center justify-content-center">
                    <module type="btn" button_style="btn-primary" text="Get Started"/>
                    <module type="btn" button_style="btn-outline-primary" text="Get Started"/>
                </div>
            </div>
            <div class="mw-new-7-container---m">
                <div class="mw-new-7-hero-composite">
                    <img loading="lazy" class="mw-new-7-rounded-image" src="{{ asset('templates/big/img/layouts/gallery-1-13.jpg') }}" alt=""/>
                    <div class="mw-new-7widget mw-new-7-notification mw-new-7-overlap-hero-image mw-new-7widget-body mw-new-7-align-center background-color-element element">
                        <div class="mw-new-7-icon-wrapper background-color-element element">
                            <i class="mdi mdi-check" style="color: #fff; font-size: 30px;"></i>
                        </div>
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-bold">Your case has been marked complete.</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="small-text muted">5 mins ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" height="130px" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

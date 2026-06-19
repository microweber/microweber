{{--
 type: layout
 name: Design 1
 position: 101
 categories: Design
--}}

<style>
    .mw-new-layouts-1 img {
        border-style: initial;
        border-width: 0;
        display: inline-block;
        height: auto;
        max-width: 100%;
        vertical-align: middle;
    }

    .mw-new-layouts-1 a {
        font-weight: 400;
        text-decoration: none;
    }

    @media screen and (max-width: 479px) {
        .mw-new-layouts-1 .inherited-styles-for-exported-element {
            font-size: 15px;
        }
    }

    .mw-new-layouts-1 .w-inline-block {
        max-width: 100%;
    }

    .mw-new-layouts-1 .mw-layout-new-1-large-text {
        font-size: 23px;
        letter-spacing: -.01em;
        line-height: 1.3em;
    }

    .mw-new-layouts-1 .small-text {
        font-size: 14px;
        line-height: 1.35em;
    }

    .mw-new-layouts-1 .mw-layout-new-1-heading-three {
        color: #222;
        font-size: 32px;
        font-weight: 400;
        letter-spacing: -.01em;
        line-height: 1.25em;
    }

    .mw-new-layouts-1 .mw-layout-new-1-single-plan {
        background-color: #fff;
        border: 1px solid rgba(34, 34, 34, .1);
        border-image: none 100% 1 0 stretch;
        border-radius: 12px;
        box-shadow: rgba(0, 0, 0, .09) 0 1px 4px;
        column-gap: 24px;
        display: flex;
        padding: 24px 36px;
        row-gap: 24px;
    }

    .mw-new-layouts-1 .mw-layout-new-1-large-text.mw-layout-new-1-single-plan-description, .mw-layouts-new-1-plan-image {
        max-width: 323px;
    }

    .mw-new-layouts-1 .mw-layout-new-1-single-plan-contents {
        column-gap: 36px;
        flex: 1;
        flex-direction: column;
        justify-content: center;
        row-gap: 36px;
        text-align: center;
    }

    .mw-new-layouts-1 .mw-layout-new-1-price, .mw-layout-new-1-price-terms, .mw-layout-new-1-single-plan-contents {
        align-items: center;
        display: flex;
    }

    .mw-new-layouts-1 .mw-layout-new-1-price-terms {
        column-gap: 12px;
        flex-direction: column;
        row-gap: 12px;
    }

    .mw-new-layouts-1 .mw-layout-new-1-price {
        column-gap: 6px;
        row-gap: 6px;
    }

    .mw-new-layouts-1 .mw-layout-new-1-large-price {
        color: #222;
        font-size: 128px;
        letter-spacing: -.02em;
        line-height: 1em;
    }

    .mw-new-layouts-1 .logo-section {
        align-items: center;
        align-self: stretch;
        flex-direction: column;
        text-align: center;
    }

    .mw-new-layouts-1 .mw-layouts-new-1-plan-image {
        max-height: 480px;
    }

    @media screen and (max-width: 1380px) {
        .mw-new-layouts-1 .mw-layouts-new-1-plan-image {
            max-width: 360px;
        }

        .mw-new-layouts-1 .mw-layout-new-1-large-price {
            font-size: 96px;
        }
    }

    @media screen and (max-width: 991px) {
        .mw-new-layouts-1 .mw-layout-new-1-heading-three {
            font-size: 28px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-layouts-1 .mw-layout-new-1-large-text {
            font-size: 20px;
            line-height: 1.35em;
        }

        .mw-new-layouts-1 .mw-layout-new-1-heading-three {
            font-size: 24px;
        }

        .mw-new-layouts-1 .mw-layout-new-1-single-plan {
            align-items: center;
            flex-direction: column-reverse;
            padding-bottom: 36px;
            padding-top: 36px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-layouts-1 .mw-layout-new-1-large-text, .small-text {
            font-size: 17px;
            line-height: 1.35em;
        }

        .mw-new-layouts-1 .small-text {
            font-size: 12px;
        }

        .mw-new-layouts-1 .mw-layout-new-1-heading-three {
            font-size: 21px;
        }

        .mw-new-layouts-1 .mw-layouts-new-1-plan-image {
            max-width: 200px;
        }

        .mw-new-layouts-1 .mw-layout-new-1-single-plan-contents {
            column-gap: 24px;
            row-gap: 24px;
        }

        .mw-new-layouts-1 .mw-layout-new-1-large-price {
            font-size: 84px;
        }

        .mw-new-layouts-1 .logo-marquee, .logo-marquee-wrapper {
            column-gap: 36px;
            row-gap: 36px;
        }
    }

    .mw-new-layouts-1 a:active, a:hover {
        outline: 0;
    }

    .mw-new-layouts-1 .small-text.muted {
        opacity: .7;
    }

    @media screen and (max-width: 479px) {
        .mw-new-layouts-1 .small-text.muted {
            font-size: 13px;
        }
    }

    .mw-new-layouts-1 h1 {
        color: #222;
        font-size: 64px;
        font-weight: 400;
        letter-spacing: -.02em;
        line-height: 1em;
        margin: 0;
    }

    @media screen and (max-width: 991px) {
        .mw-new-layouts-1 h1 {
            font-size: 58px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-layouts-1 h1 {
            font-size: 54px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-layouts-1 h1 {
            font-size: 39px;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-1"
    field-name="layout-new-layouts-skin-1"
    container-class="mw-layout-container container no-element text-center edit safe-mode"
>
    <div class="mb-5">
                <h2 class="mw-new-7-heading-one" data-mwplaceholder="{{ _e('Enter title here') }}">All-in-one legal</h2>
                <h2 class="mw-new-7-heading-one" data-mwplaceholder="{{ _e('Enter title here') }}" style="font-style: italic;">for your business.</h2>
            </div>

            <div class="mw-layout-new-1-single-plan">
                <img loading="lazy" class="mw-layouts-new-1-plan-image" src="{{ asset('templates/big/img/layouts/product-1.jpg') }}" alt=""/>

                <div class="mw-layout-new-1-single-plan-contents">
                    <div class="mw-layout-new-1-price-terms">
                        <div class="mw-layout-new-1-price">
                            <div class="mw-layout-new-1-heading-three">$</div>
                            <div class="mw-layout-new-1-large-price">99</div>
                            <div>/ month</div>
                        </div>
                        <div data-mwplaceholder="{{ _e('Enter text here') }}" class="small-text muted">Prices in USD, billed annually.</div>
                    </div>
                    <div data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-layout-new-1-large-text mw-layout-new-1-single-plan-description">Comprehensive legal tools for growing enterprises.</div>
                    <module type="btn" button_style="btn-primary" text="Get Started"/>
                </div>
            </div>
</x-layout-section>

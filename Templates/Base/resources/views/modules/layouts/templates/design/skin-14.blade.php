{{--
type: layout
name: Design 14
position: 114
categories: Design
--}}

<style>
    a {
        font-weight: 400;
        text-decoration: none;
    }

    @media screen and (max-width: 479px) {
        .inherited-styles-for-exported-element {
            font-size: 15px;
        }
    }

    .font-weight-bold {
        font-variation-settings: "wght" 600;
    }

    .heading-two {
        color: #222;
        font-size: 45px;
        font-weight: 400;
        letter-spacing: -.01em;
        line-height: 1.15em;
    }

    .rounded-image {
        border-radius: 10px;
        display: block;
    }

    .customer-quote-grid {
        align-items: center;
        column-gap: 24px;
        display: grid;
        grid-auto-columns: 1fr;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto;
        justify-items: center;
        row-gap: 24px;
    }

    .customer-cta {
        align-items: center;
        column-gap: 30px;
        display: flex;
        flex-direction: column;
        row-gap: 30px;
        text-align: center;
    }

    @media screen and (max-width: 991px) {
        .heading-two {
            font-size: 42px;
        }

        .customer-quote-grid {
            column-gap: 72px;
            display: flex;
            flex-direction: column;
            row-gap: 72px;
        }
    }

    @media screen and (max-width: 767px) {
        .heading-two {
            font-size: 36px;
        }

        .customer-quote-grid {
            column-gap: 48px;
            row-gap: 48px;
        }
    }

    @media screen and (max-width: 479px) {
        .heading-two {
            font-size: 28px;
        }

        .rounded-image {
            border-radius: 6px;
        }
    }

    .heading-two.italic {
        font-style: italic;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-14"
    field-name="layout-new-layouts-skin-14"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="customer-quote-grid">
                    <div>
                        <img loading="lazy" class="rounded-image" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt=""/>
                    </div>

                    <div class="customer-cta">
                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="heading-two italic">“CMS has become an indispensable part of our business.”</h2>
                        <div>
                            <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold mb-0">Sandra Hotchkins</h6>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Founder + CEO, Ableto</p>
                        </div>

                        <module type="btn" button_style="btn-primary" text="Meet our Customers"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>

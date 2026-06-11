{{--
type: layout
name: Design 20
position: 120
categories: Design
--}}

<style>
    a {
        font-weight: 400;
        text-decoration: none;
    }

    .heading-two {
        color: #222;
        font-size: 45px;
        font-weight: 400;
        letter-spacing: -.01em;
        line-height: 1.15em;
    }

    .text-bold {
        color: #222;
        font-variation-settings: "wght" 600;
    }

    .text-center {
        text-align: center;
    }

    .faq-cta {
        align-items: center;
        column-gap: 18px;
        display: flex;
        row-gap: 18px;
    }

    @media screen and (max-width: 991px) {
        .heading-two {
            font-size: 42px;
        }
    }

    @media screen and (max-width: 767px) {
        .heading-two {
            font-size: 36px;
        }
    }

    @media screen and (max-width: 479px) {
        .heading-two {
            font-size: 28px;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-20"
    field-name="layout-new-layouts-skin-20"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row class="col-xl-8 mx-auto gap-5">
                <div class="heading-two text-center">Frequently asked questions</div>

                <module type="accordion" template="skin-6" id="accordion-layout--{{ $params['id'] }}"/>

                <div class="faq-cta text-center justify-content-center">
                    <span>Do you still have questions about CMS?</span>
                    <module type="btn" button_style="btn-outline-primary" text="Get in touch"/>
                </div>
            </x-row>
</x-layout-section>

{{--
type: layout
name: Design 12
position: 112
categories: Design
--}}

<style>
    .mw-new-layouts-12 {
        .mw-new-12-large-text {
            font-size: 23px;
            letter-spacing: -.01em;
            line-height: 1.3em;
        }

        .mw-new-12-heading-one {
            font-weight: 400;
            letter-spacing: -.02em;
            line-height: 1em;
        }

        .mw-new-12-horizontal-buttons {
            align-items: center;
            column-gap: 18px;
            display: flex;
            row-gap: 18px;
        }

        .mw-new-12-cta-box {
            background-color: #222;
            border-radius: 12px;
            color: rgba(255, 255, 255, .9);
            display: flex;
            justify-content: center;
            overflow: hidden;
            padding: 72px 24px 96px;
            position: relative;
            text-align: center;
        }

        .mw-new-12-cta-contents {
            align-items: center;
            column-gap: 36px;
            display: flex;
            flex-direction: column;
            position: relative;
            row-gap: 36px;
        }

        @media screen and (max-width: 991px) {
            .mw-new-12-heading-one {
                font-size: 58px;
            }

            .mw-new-12-cta-box {
                overflow: hidden;
            }

            .mw-new-12-section-logo {
                transform: translate(-50%);
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-12-large-text {
                font-size: 20px;
                line-height: 1.35em;
            }

            .mw-new-12-heading-one {
                font-size: 54px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-12-large-text {
                font-size: 17px;
                line-height: 1.35em;
            }

            .mw-new-12-heading-one {
                font-size: 39px;
                line-height: 1.15em;
            }

            .mw-new-12-horizontal-buttons {
                align-items: stretch;
                align-self: stretch;
                flex-direction: column;
            }

            .mw-new-12-cta-box {
                padding-bottom: 48px;
                padding-top: 48px;
            }
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-12 mw-layout-dark-background"
    field-name="layout-new-layouts-skin-12"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="mw-new-12-cta-box">
                    <div class="mw-new-12-cta-contents">
                        <div>
                            <h2 class="mw-new-12-heading-one" data-mwplaceholder="{{ _e('Enter title here') }}">On-demand Legal</h2>
                            <h2 class="mw-new-12-heading-one" data-mwplaceholder="{{ _e('Enter title here') }}" style="font-style: italic;">with CMS.</h2>
                        </div>

                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-12-large-text">Effortlessly access specialized legal guidance tailored for the needs of new and growing businesses.</p>
                        <div class="mw-new-12-horizontal-buttons">
                            <module type="btn" button_style="btn-primary" text="Get Started"/>
                            <module type="btn" button_style="btn-outline-primary" text="Request a Demo"/>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

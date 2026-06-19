{{--
type: layout
name: Design 17
position: 117
categories: Design
--}}

<style>
    .mw-new-layouts-17 {
        .mw-new-17-title-tag {
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.1;
        }

        .mw-new-17-photo-wrapper {
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        .mw-new-17-photo {
            height: 100%;
            object-fit: cover;
            object-position: 50% 0%;
            width: 100vw;
            min-height: 650px;
        }

        .flex-right, .flex-tag {
            display: flex;
        }

        .flex-tag {
            align-items: center;
            background-color: var(--mw-primary-color);
            border-color: #c7c3cf;
            border-radius: 30px;
            border-width: 2px;
            column-gap: 10px;
            margin-bottom: 30px;
            padding: 10px 20px;
        }

        .mw-new-17-gray-color {
            opacity: .4;
        }

        .mw-new-17-subhead-main {
            color: #000;
            font-size: 25px;
            font-weight: 600;
            line-height: 1.4;
            opacity: 1;
        }

        @media screen and (max-width: 767px) {
            .mw-new-17-subhead-main {
                font-size: 28px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-17-subhead-main {
                font-size: 25px;
                max-width: 100%;
            }
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-17"
    field-name="layout-new-layouts-skin-17"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="col-md-6 col-sm-10 mx-auto d-flex flex-column justify-content-between mb-md-0 mb-4">
                    <div class="flex-right">
                        <div class="flex-tag background-color-element element" style="opacity: 1;">
                            <span class="mw-new-17-title-tag">why us</span>
                        </div>
                    </div>

                    <p data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-17-subhead-main">SkyRocket — Crafting Intuitive User Experiences. We're proud recipients of 10 awards in website design.
                        <span class="mw-new-17-gray-color">Explore our portfolio for in-depth insights into our acclaimed projects.</span>
                    </p>

                    <div class="mt-5">
                        <module type="btn" button_style="btn-outline-primary" text="ALL WORKS"/>
                    </div>
                </div>

                <div class="col-md-6 col-sm-10 mx-auto mw-new-17-photo-wrapper">
                    <img class="mw-new-17-photo" loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt=""/>
                </div>
            </x-row>
</x-layout-section>

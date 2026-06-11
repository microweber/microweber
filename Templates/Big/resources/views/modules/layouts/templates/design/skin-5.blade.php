{{--
 type: layout
 name: Design 5
 position: 105
 categories: Design
--}}

<style>
    .mw-new-layouts-5 {
        .mw-new-5-title {
            color: #222;
            font-weight: 400;
            letter-spacing: -.02em;
            line-height: 1em;
            margin: 0;
        }

        @media screen and (max-width: 991px) {
            h1 {
                font-size: 58px;
            }
        }

        @media screen and (max-width: 767px) {
            h1 {
                font-size: 54px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-5-inherited-styles-for-exported-element {
                font-size: 15px;
            }

            h1 {
                font-size: 39px;
            }
        }

        .mw-new-5-small-text {
            font-size: 14px;
            line-height: 1.35em;
        }

        .mw-new-5-heading-four {
            font-size: 23px;
            line-height: 1.3em;
        }

        .mw-new-5-heading-four, .font-weight-bold {
            color: #222;
            font-variation-settings: "wght" 600;
        }

        .mw-new-5-container---m {
            max-width: 671px;
            width: 100%;
        }

        .mw-new-5-rounded-image {
            border-radius: 10px;
            display: block;
        }

        .mw-new-5-review-sites-box {
            align-items: center;
            background-color: #F9F9F9;
            border: 1px solid rgba(34, 34, 34, .1);
            border-image: none 100% 1 0 stretch;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, .07) 0 1px 4px;
            column-gap: 36px;
            display: flex;
            padding: 16px 48px;
            row-gap: 36px;
        }

        .mw-new-5-review-box-item {
            column-gap: 12px;
            display: flex;
            row-gap: 12px;
            text-align: left;
        }

        .mw-new-5-review-box-item i {
            color: #0CAA41;
            font-size: 30px;
            align-self: center;
        }

        .mw-new-5-review-box-divider {
            align-self: stretch;
            background-color: rgba(34, 34, 34, .2);
            width: 1px;
        }

        .mw-new-5-career-intro-section, .mw-new-5-review-items {
            align-items: center;
            column-gap: 36px;
            display: flex;
            row-gap: 36px;
        }

        .mw-new-5-career-intro-section {
            column-gap: 96px;
            flex-direction: column;
            row-gap: 96px;
            text-align: center;
        }

        .mw-new-5-career-photo-cluster {
            align-items: center;
            align-self: stretch;
            column-gap: 12px;
            display: flex;
            justify-content: center;
            row-gap: 12px;
        }

        .mw-new-5-photo-cluster-column {
            align-items: flex-end;
            column-gap: 12px;
            display: flex;
            flex-direction: column;
            max-width: 516px;
            row-gap: 12px;
        }

        @media screen and (max-width: 767px) {
            .mw-new-5-heading-four {
                font-size: 21px;
            }

            .mw-new-5-review-sites-box {
                flex-direction: column;
                padding-bottom: 24px;
                padding-top: 24px;
            }

            .mw-new-5-review-box-divider {
                display: none;
            }

            .mw-new-5-career-intro-section {
                column-gap: 72px;
                row-gap: 72px;
            }

            .mw-new-5-photo-cluster-column {
                max-width: 50%;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-5-small-text {
                font-size: 12px;
                line-height: 1.35em;
            }

            .mw-new-5-heading-four {
                font-size: 18px;
            }

            .mw-new-5-rounded-image {
                border-radius: 6px;
            }

            .mw-new-5-review-sites-box {
                column-gap: 24px;
                padding-left: 24px;
                padding-right: 24px;
                row-gap: 24px;
            }

            .mw-new-5-review-box-divider {
                height: 1px;
                opacity: .7;
                width: 100%;
            }

            .mw-new-5-review-items {
                align-self: stretch;
                column-gap: 18px;
                flex-direction: column;
                row-gap: 18px;
            }

            .mw-new-5-career-intro-section {
                column-gap: 60px;
                row-gap: 60px;
            }
        }

        .mw-new-5-rounded-image.mw-new-5-small-square-image {
            max-width: 323px;
        }

        @media screen and (max-width: 767px) {
            .mw-new-5-rounded-image.mw-new-5-small-square-image {
                max-width: 170px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-5-rounded-image.mw-new-5-small-square-image {
                max-width: 80%;
            }
        }

        @media screen and (max-width: 991px) {
            .mw-new-5-rounded-image.mw-new-5-small-square-image.mw-new-5-inside-cluster {
                display: none;
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-5-rounded-image.mw-new-5-small-square-image.mw-new-5-adjacent-to-column {
                max-width: 50%;
            }
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-5"
    field-name="layout-new-layouts-skin-5"
    container-class="mw-layout-container no-element edit safe-mode no-typing"
>
    <div class="mw-new-5-career-intro-section mw-new-5-inherited-styles-for-exported-element">
                <div>
                    <h2 class="mw-new-5-title" data-mwplaceholder="{{ _e('Enter title here') }}">Join the</h2>
                    <h2 style="font-style: italic;">CMS Revolution.</h2>
                </div>
                <div class="mw-new-5-career-photo-cluster">
                    <div class="mw-new-5-photo-cluster-column">
                        <img loading="lazy" class="mw-new-5-rounded-image" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                        <img loading="lazy" class="mw-new-5-rounded-image mw-new-5-small-square-image" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt=""/>
                    </div>

                    <img loading="lazy" class="mw-new-5-rounded-image mw-new-5-small-square-image mw-new-5-adjacent-to-column" src="{{ asset('templates/big/img/layouts/gallery-1-vertical.jpg') }}" alt=""/>
                    <img loading="lazy" class="mw-new-5-rounded-image mw-new-5-small-square-image mw-new-5-inside-cluster" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                </div>
                <div class="mw-new-5-review-sites-box background-color-element element">
                    <div data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-5-heading-four">95% Employee Satisfaction</div>
                    <div class="mw-new-5-review-box-divider"></div>
                    <div class="mw-new-5-review-items ">
                        <div class="mw-new-5-review-box-item">
                            <i class="safe-element mdi mdi-phone"></i>
                            <div>
                                <div data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-5-small-text font-weight-bold">via Glassdoor</div>
                                <div data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-5-small-text">35+ Reviews</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-layout-section>

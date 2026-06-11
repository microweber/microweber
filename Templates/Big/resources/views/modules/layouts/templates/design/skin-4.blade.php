{{--
 type: layout
 name: Design 4
 position: 104
 categories: Design
--}}

<style>
    .mw-new-layouts-4 {
        a, h1 {
            color: #222;
            font-weight: 400;
        }

        h1 {
            letter-spacing: -.02em;
            line-height: 1em;
            margin: 0;
        }

        a {
            font-variation-settings: "wght" 550;
            text-decoration: none;
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
            .mw-new-4-inherited-styles-for-exported-element {
                font-size: 15px;
            }

            h1 {
                font-size: 39px;
            }
        }

        .w-inline-block {
            max-width: 100%;
        }

        .mw-new-4-heading-four {
            color: #222;
            font-size: 23px;
            font-variation-settings: "wght" 600;
            line-height: 1.3em;
        }

        .mw-new-4-container---l {
            max-width: 902px;
            width: 100%;
        }

        .mw-new-4-about-hero-wrapper {
            align-items: center;
            column-gap: 96px;
            display: flex;
            flex-direction: column;
            row-gap: 96px;
            text-align: center;
        }

        .mw-new-4-container---m {
            max-width: 671px;
            width: 100%;
        }

        .mw-new-4-about-photo-video, .mw-new-4-about-photos {
            align-items: center;
            display: flex;
            flex-direction: column;
        }

        .mw-new-4-about-photos {
            align-items: flex-start;
            align-self: stretch;
            margin-bottom: -72px;
        }

        .mw-new-4-rounded-image {
            border-radius: 10px;
            display: block;
        }

        .mw-new-4-video-box, .mw-new-4-video-box-video {
            align-items: center;
            display: flex;
        }

        .mw-new-4-video-box {
            background-color: rgba(255, 255, 255, .9);
            border: 1px solid rgba(34, 34, 34, .2);
            border-image: none 100% 1 0 stretch;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, .09) 0 1px 5px;
            column-gap: 30px;
            padding: 12px 24px 12px 12px;
            position: relative;
            row-gap: 30px;
            text-align: left;
            z-index: 2;
        }

        .mw-new-4-video-box-video {
            flex: 1;
            justify-content: center;
            max-width: 222px;
        }

        .video-image {
            border-radius: 8px;
        }

        .mw-new-4-video-text {
            column-gap: 6px;
            display: flex;
            flex: 1;
            flex-direction: column;
            row-gap: 6px;
        }

        .mw-new-4-video-lightbox {
            align-items: center;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            height: 54px;
            justify-content: center;
            position: absolute;
            transition: box-shadow .2s, transform .2s;
            transition-behavior: normal, normal;
            width: 54px;
        }

        .video-play-icon {
            left: 1px;
            position: relative;
            top: 1px;
        }

        @media screen and (max-width: 767px) {
            .mw-new-4-heading-four {
                font-size: 21px;
            }

            .mw-new-4-about-hero-wrapper {
                column-gap: 60px;
                row-gap: 60px;
            }

            .mw-new-4-about-photo-video {
                column-gap: 24px;
                row-gap: 24px;
            }

            .mw-new-4-about-photos {
                margin-bottom: 0;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-4-heading-four {
                font-size: 18px;
            }

            .mw-new-4-rounded-image, .mw-new-4-video-box {
                border-radius: 6px;
            }

            .mw-new-4-video-box {
                column-gap: 24px;
                flex-direction: column;
                padding-bottom: 24px;
                padding-right: 12px;
                row-gap: 24px;
            }

            .mw-new-4-video-box-video {
                max-width: 100%;
            }

            .video-image {
                border-radius: 4px;
            }
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-4"
    field-name="layout-new-layouts-skin-4"
    container-class="mw-layout-container no-element edit safe-mode no-typing"
>
    <div class="mw-new-4-about-hero-wrapper mw-new-4-inherited-styles-for-exported-element">
                <div>
                    <h2 class="mw-new-5-title" data-mwplaceholder="{{ _e('Enter title here') }}">On a mission to</h2>
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" style="font-style: italic;">democratize legal.</h2>
                </div>

                <div class="mw-new-4-container---l">
                    <div class="mw-new-4-about-photo-video">
                        <div class="mw-new-4-about-photos">
                            <img loading="lazy" class="mw-new-4-rounded-image mw-new-4-about-image-left" src="{{ asset('templates/big/img/layouts/gallery-1-14.jpg') }}" alt=""/>
                            <img loading="lazy" class="mw-new-4-rounded-image mw-new-4-about-image-right" src="{{ asset('templates/big/img/layouts/gallery-1-vertical.jpg') }}" alt=""/>
                        </div>

                        <div class="mw-new-4-container---m">
                            <div class="mw-new-4-video-box background-color-element element">
                                <div class="mw-new-4-video-box-video">
                                    <img loading="lazy" class="video-image" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>

                                    <div class="mw-new-4-video-lightbox w-inline-block">
                                        <module type="video" template="dialog" url="{{ asset('templates/big/videos/example.mp4') }}" height="700">
                                    </div>
                                </div>

                                <div class="mw-new-4-video-text">
                                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-4-heading-four">Meet the team</h6>
                                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Get started with CMS and reduce your legal headaches.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-layout-section>

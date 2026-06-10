{{--
 type: layout
 name: Design 6
 position: 106
 categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-new-layouts-6 {
        .mw-new-6-container---main {
            margin-left: auto;
            margin-right: auto;
            max-width: 1414px;
            padding-left: 24px;
            padding-right: 24px;
            width: 100%;
        }

        .mw-new-6-heading-three {
            color: #222;
            font-size: 32px;
            font-weight: 400;
            letter-spacing: -.01em;
            line-height: 1.25em;
        }

        .font-weight-bold {
            color: #222;
            font-variation-settings: "wght"600;
        }

        .mw-new-6-rounded-image {
            border-radius: 10px;
            display: block;
        }

        .mw-new-6-story-grid {
            align-items: center;
            column-gap: 24px;
            display: grid;
            grid-auto-columns: 1fr;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto;
            justify-items: center;
            row-gap: 24px;
        }

        .mw-new-6-container---s {
            max-width: 555px;
            width: 100%;
        }

        .story-text {
            align-items: flex-start;
            column-gap: 30px;
            display: flex;
            flex-direction: column;
            row-gap: 30px;
        }

        .mw-new-6-career-image-pair {
            align-items: flex-end;
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        .mw-new-6-career-image-1 {
            max-width: 323px;
        }

        @media screen and (max-width: 991px) {
            .mw-new-6-heading-three {
                font-size: 28px;
            }

            .mw-new-6-story-grid {
                column-gap: 72px;
                display: flex;
                flex-direction: column;
                row-gap: 72px;
            }

            .mw-new-6-career-image-pair {
                margin-bottom: 72px;
            }
        }

        @media screen and (max-width: 767px) {
            .section {
                padding-bottom: 72px;
                padding-top: 72px;
            }

            .mw-new-6-heading-three {
                font-size: 24px;
            }

            .mw-new-6-story-grid {
                column-gap: 60px;
                row-gap: 60px;
            }

            .story-text {
                column-gap: 24px;
                row-gap: 24px;
            }
        }

        @media screen and (max-width: 479px) {
            .section {
                padding-bottom: 72px;
                padding-top: 72px;
            }

            .mw-new-6-container---main {
                padding-left: 18px;
                padding-right: 18px;
            }

            .mw-new-6-heading-three {
                font-size: 21px;
            }

            .mw-new-6-rounded-image {
                border-radius: 6px;
            }

            .mw-new-6-story-grid {
                column-gap: 48px;
                row-gap: 48px;
            }
        }

        .mw-new-6-rounded-image.mw-new-6-career-image-1 {
            margin-right: -72px;
            transform: rotate(-2deg);
        }

        .mw-new-6-rounded-image.mw-new-6-career-image-2 {
            max-width: 323px;
            position: relative;
            top: 72px;
            transform: rotate(2deg);
        }

        @media screen and (max-width: 767px) {
            .mw-new-6-rounded-image.mw-new-6-career-image-1 {
                margin-right: -48px;
                max-width: 250px;
            }

            .mw-new-6-rounded-image.mw-new-6-career-image-2 {
                max-width: 250px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-6-rounded-image.mw-new-6-career-image-1, .mw-new-6-rounded-image.mw-new-6-career-image-2 {
                max-width: 150px;
            }
        }
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-6 mw-new-6-inherited-styles-for-exported-element">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" height="40px" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="mw-new-6-container---main mw-layout-container no-element edit safe-mode no-typing"
         field="layout-new-layouts-skin-6-{{ $params['id'] }}" rel="module">
        <div class="mw-new-6-story-grid">
            <div class="mw-new-6-career-image-pair">
                <img loading="lazy" class="mw-new-6-rounded-image mw-new-6-career-image-1" src="{{ asset('templates/big/img/layouts/gallery-1-vertical.jpg') }}" alt=""/>
                <img loading="lazy" class="mw-new-6-rounded-image mw-new-6-career-image-2" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
            </div>

            <div class="mw-new-6-container---s">
                <div data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold mb-4">From the Team</div>
                <div data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-6-heading-three">“Working at CMS is an ever-evolving journey. Here, innovation and collaboration go hand in hand, creating an environment where every day brings new challenges and opportunities to grow both personally and professionally.”</div>
                <div class="mt-4">
                    <div data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Carly Graham</div>
                    <div data-mwplaceholder="{{ _e('Enter text here') }}">CMS Team Member</div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" height="130px" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

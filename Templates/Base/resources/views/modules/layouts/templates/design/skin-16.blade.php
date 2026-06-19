{{--
type: layout
name: Design 16
position: 116
categories: Design
--}}

<style>
    .mw-new-layouts-16 {
        p {
            color: #000;
            font-size: 18px;
            font-weight: 500;
            line-height: 1.3;
            margin-bottom: 10px;
            margin-top: 0;
            opacity: .75;
        }

        @media screen and (max-width: 479px) {
            p {
                font-size: 16px;
            }
        }

        .mw-new-16-title {
            font-size: 38px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 5px;
            margin-top: 0;
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

        @media screen and (max-width: 991px) {
            .mw-new-16-title {
                font-size: 45px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-16-title {
                font-size: 34px;
            }
        }

        .mw-new-16-heading-overlay.mw-new-16-_2 {
            transform: translate(0%, 100%);
        }

        @media screen and (max-width: 991px) {
            .mw-new-16-title.for-fade {
                font-size: 40px;
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-16-title.for-fade {
                font-size: 26px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-16-title.for-fade {
                font-size: 24px;
            }
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-16"
    field-name="layout-new-layouts-skin-16"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="col-md-12 col-sm-10 mx-auto mb-5">
                    <div class="flex-right">
                        <div class="flex-tag background-color-element element" style="opacity: 1;">
                            <span class="mw-new-16-title-tag">recent news</span>
                        </div>
                    </div>
                    <div>
                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-16-title for-fade" style="opacity: 0.24;">LATEST INSIGHT</h2>
                    </div>
                </div>

                <module type="posts" template="skin-21"/>
            </x-row>
</x-layout-section>

{{--
 type: layout
 name: Design 8
 position: 108
 categories: Design
--}}

<style>
    .mw-new-layouts-8 {
        a, .mw-new-8-title {
            font-weight: 400;
        }

        .mw-new-8-title {
            color: #222;
            font-size: 45px;
            letter-spacing: -.01em;
            line-height: 1.15em;
            margin-bottom: 0;
            margin-top: 0;
        }

        a {
            text-decoration: none;
        }

        @media screen and (max-width: 991px) {
            .mw-new-8-title {
                font-size: 42px;
            }
        }

        @media screen and (max-width: 767px) {
            .mw-new-8-title {
                font-size: 36px;
            }
        }

        @media screen and (max-width: 479px) {
            .inherited-styles-for-exported-element {
                font-size: 15px;
            }

            .mw-new-8-title {
                font-size: 28px;
            }
        }
    }

    .w-inline-block {
        max-width: 100%;
    }

    .container---m {
        max-width: 671px;
        width: 100%;
    }

    .overlapping-avatars {
        align-items: center;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .overlapping-avatar {
        border: 3px solid #fff;
        border-image: none 100% 1 0 stretch;
        border-radius: 50%;
        display: block;
        left: -9px;
        margin-right: -18px;
        max-height: 96px;
        position: relative;
        z-index: 2;
    }

    @media screen and (max-width: 767px) {
        .overlapping-avatar {
            max-height: 72px;
        }
    }

    .container---m.title {
        align-items: center;
        column-gap: 36px;
        display: flex;
        flex-direction: column;
        row-gap: 36px;
        text-align: center;
    }

    .overlapping-avatars.inside-title {
        margin-bottom: -12px;
        margin-top: 0;
    }

    @media screen and (max-width: 479px) {
        .container---m.title {
            column-gap: 30px;
            row-gap: 30px;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-8"
    field-name="layout-new-layouts-skin-8"
    container-class="mw-layout-container container no-element edit safe-mode text-center"
>
    <div class="col-lx-8 col-12 mx-auto">
                <div class="overlapping-avatars inside-title mb-5">
                    <img loading="lazy" class="overlapping-avatar cloneable p-0 element" src="{{ asset('templates/big/img/layouts/teamcard/1.jpg') }}" alt=""/>
                    <img loading="lazy" class="overlapping-avatar cloneable p-0 element" src="{{ asset('templates/big/img/layouts/teamcard/2.jpg') }}" alt=""/>
                    <img loading="lazy" class="overlapping-avatar cloneable p-0 element" src="{{ asset('templates/big/img/layouts/teamcard/3.jpg') }}" alt=""/>
                </div>

                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-8-title mb-5 col-xxl-6 col-12 mx-auto">Top-notch legal support when your business needs it.</h2>

                <module type="btn" button_style="btn-primary" text="Schedule a demo"/>
            </div>
</x-layout-section>

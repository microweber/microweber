{{--
type: layout
name: Design 15
position: 115
categories: Design
--}}

<style>
    .mw-new-layouts-15 {
        .mw-new-15-title-tag {
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.1;
        }

        .mw-new-15-title {
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

        .mw-new-15-work-wrapper {
            align-items: flex-end;
            cursor: pointer;
            display: flex;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            position: relative;
        }

        .mw-new-15-work-photo {
            height: 100%;
            object-fit: cover;
            width: 100%;
            min-height: 550px;
        }

        @media screen and (max-width: 991px) {
            .mw-new-15-title {
                font-size: 45px;
            }
        }

        @media screen and (max-width: 479px) {
            .mw-new-15-title {
                font-size: 34px;
            }
        }

        .mw-new-15-work-photo {
            transition: transform 0.3s ease-in-out; /* Animation effect */
            transform-origin: center; /* Set the origin of transformation to the center of the image */
        }

        .mw-new-15-work-wrapper .mw-new-15-work-photo:hover {
            transform: scale(1.2) rotate(-5deg); /* Zoom and rotate on hover */
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-15"
    field-name="layout-new-layouts-skin-15"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row class="col-xl-12 mx-auto">
                <div class="flex-right">
                    <div class="flex-tag background-color-element element" style="opacity: 1;">
                        <span class="mw-new-15-title-tag">recent works</span>
                    </div>
                </div>
                <div>
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-15-title">THE WORK WE DO,</h2>
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-15-title">AND THE PEOPLE WE HELP.</h2>
                </div>

                <div class="col-md-6 col-sm-10 col-12 mx-auto mt-5 cloneable element">
                    <a>
                        <div class="mw-new-15-work-wrapper">
                            <img loading="lazy" class="mw-new-15-work-photo" src="{{ asset('templates/big/img/layouts/gallery-1-14.jpg') }}" alt=""/>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-10 col-12 mx-auto mt-5 cloneable element">
                    <a>
                        <div class="mw-new-15-work-wrapper">
                            <img loading="lazy" class="mw-new-15-work-photo" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-10 col-12 mx-auto mt-5 cloneable element">
                    <a>
                        <div class="mw-new-15-work-wrapper">
                            <img loading="lazy" class="mw-new-15-work-photo" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-10 col-12 mx-auto mt-5 cloneable element">
                    <a>
                        <div class="mw-new-15-work-wrapper">
                            <img loading="lazy" class="mw-new-15-work-photo" src="{{ asset('templates/big/img/layouts/gallery-1-12.jpg') }}" alt=""/>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-10 col-12 mx-auto mt-5 cloneable element">
                    <a>
                        <div class="mw-new-15-work-wrapper">
                            <img loading="lazy" class="mw-new-15-work-photo" src="{{ asset('templates/big/img/layouts/gallery-1-15.jpg') }}" alt=""/>
                        </div>
                    </a>
                </div>

                <div class="col-md-6 col-sm-10 col-12 mx-auto mt-5 cloneable element">
                    <a>
                        <div class="mw-new-15-work-wrapper">
                            <img loading="lazy" class="mw-new-15-work-photo" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt=""/>
                        </div>
                    </a>
                </div>
            </x-row>
</x-layout-section>

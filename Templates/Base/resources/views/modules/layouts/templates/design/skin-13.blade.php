{{--
type: layout
name: Design 13
position: 113
categories: Design
--}}

<style>
    a {
        background-color: transparent;
        color: #747474;
        text-decoration: underline;
    }

    p {
        color: rgba(77, 77, 77, .9);
        line-height: 1.4;
        margin-top: 0;
    }

    .mw-new-13-title {
        font-size: 38px;
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 0;
        margin-top: 0;
    }

    .mw-new-13-button-with-line {
        color: #333;
        position: relative;
        text-decoration: none;
    }

    .mw-new-13-button-line-first {
        background-color: rgba(255, 255, 255, .2);
        height: 1px;
        margin-top: 1px;
        position: relative;
        width: 100%;
    }

    .mw-new-13-button-line-overlay {
        background-color: #555;
        height: 2px;
        position: absolute;
        width: 100%;
        z-index: 1;
    }

    .mw-new-13-button-text {
        height: 20px;
    }

    .mw-new-13-button-text-wrapper {
        height: 24px;
        overflow: hidden;
    }

    .mw-new-13-top-divider {
        border-bottom-color: #dcf2f4;
        border-bottom-width: 1px;
    }

    @media screen and (max-width: 991px) {
        .mw-new-13-title {
            font-size: 40px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-13-title {
            font-size: 30px;
            margin-bottom: 0;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-13-title {
            margin-top: 15px;
        }
    }

    a:active, a:hover {
        outline: 0;
    }

    .mw-new-13-button-with-line.mw-new-13-_2 {
        align-items: center;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0;
        line-height: 24px;
    }

    .mw-new-13-top-divider.mw-new-13-block {
        align-items: center;
        border-bottom-style: none;
        display: flex;
        justify-content: space-between;
        margin-bottom: 50px;
        padding-bottom: 0;
    }

    @media screen and (max-width: 767px) {
        .mw-new-13-top-divider.mw-new-13-block {
            align-items: flex-start;
            flex-direction: column;
            row-gap: 20px;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-13"
    field-name="layout-new-layouts-skin-13"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="mw-new-13-top-divider mw-new-13-block">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-13-title">Meet the team</h3>
                    <a class="mw-new-13-button-with-line mw-new-13-_2">
                        <div class="mw-new-13-button-text-wrapper">
                            <div class="mw-new-13-button-text">Join us</div>
                        </div>
                        <div class="mw-new-13-button-line-first">
                            <div class="mw-new-13-button-line-overlay" style="width: 0%; height: 2px;"></div>
                        </div>
                    </a>
                </div>
                <div>
                    <module type="teamcard" template="skin-18"/>
                </div>
            </x-row>
</x-layout-section>

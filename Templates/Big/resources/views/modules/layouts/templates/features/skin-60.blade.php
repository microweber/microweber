{{--
 type: layout
 name: Feature 60
 position: 60
 categories: Features
--}}

<style>
    .mw-featured4 {
        /* Bootstrap variables */
        --bs-body-color: #28303b;
        --bs-body-bg: rgb(255, 255, 255);

        /* Easy Frontend variables */
        --ezy-theme-color: rgb(13, 110, 253);
        --ezy-theme-color-rgb: 13, 110, 253;
        --ezy-item-bg: rgb(246, 246, 246);
        --ezy-item-icon-bg: rgb(255, 255, 255);
        --ezy-item-icon-shodow: 0 4px 34px 0 rgba(163, 190, 241, 0.12);

        background-color: var(--bs-body-bg);
        overflow: hidden;
        padding: 60px 0;
        position: relative;
        z-index: 1;
    }

    @media (min-width: 768px) {
        .mw-featured4 {
            padding: 100px 0;
        }
    }

    /* Gray Block Style */
    .gray .mw-featured4,
    .mw-featured4.gray {
        /* Bootstrap variables */
        --bs-body-bg: rgb(246, 246, 246);
    }

    /* Dark Gray Block Style */
    .dark-gray .mw-featured4,
    .mw-featured4.dark-gray {
        /* Bootstrap variables */
        --bs-body-color: #ffffff;
        --bs-body-bg: rgb(30, 39, 53);

        /* Easy Frontend variables */
        --ezy-item-bg: rgb(11, 23, 39);
        --ezy-item-icon-bg: rgb(30, 39, 53);
        --ezy-item-icon-shodow: 0 7px 34px 0px rgba(0, 0, 0, 1);
    }

    /* Dark Block Style */
    .dark .mw-featured4,
    .mw-featured4.dark {
        /* Bootstrap variables */
        --bs-body-color: #ffffff;
        --bs-body-bg: rgb(11, 23, 39);

        /* Easy Frontend variables */
        --ezy-item-bg: rgb(30, 39, 53);
        --ezy-item-icon-bg: rgb(11, 23, 39);
        --ezy-item-icon-shodow: 0 7px 34px rgba(0, 0, 0, 0.25);
    }

    .mw-featured4-heading {
        font-weight: bold;
        font-size: 25px;
        line-height: 25px;
        color: var(--bs-body-color);
    }

    @media (min-width: 768px) {
        .mw-featured4-heading {
            font-size: 45px;
            line-height: 45px;
        }
    }

    .mw-featured4-sub-heading {
        font-size: 18px;
        line-height: 25px;
        color: var(--bs-body-color);
    }

    .mw-featured4-item {
        background-color: var(--ezy-item-bg);
        border-radius: 20px;
    }

    [class*="mw-featured4-shape-"] {
        position: relative;
    }

    [class*="mw-featured4-shape-"]::before {
        content: "";
        background-color: var(--ezy-theme-color);
        border-radius: 20px;
        position: absolute;
        top: -25px;
        bottom: -25px;
        width: 33%;
        z-index: -1;
    }

    .mw-featured4-shape-start::before {
        left: -25px;
    }

    .mw-featured4-shape-end::before {
        right: -25px;
    }

    .mw-featured4-icon {
        width: 74px;
        height: 74px;
        background-color: var(--ezy-item-icon-bg);
        color: var(--ezy-theme-color);
        border-radius: 10px;
        font-size: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--ezy-item-icon-shodow);
        z-index: 1;
    }

    .mw-featured4-title {
        color: var(--bs-body-color);
    }

    .mw-featured4-content {
        color: var(--bs-body-color);
        opacity: 0.7;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section feature-60 mw-featured4"
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

        <div class="mw-layout-container container no-element edit" field="layout-feature-skin-60-{{ $params['id'] }}" rel="module">
            <x-row class="justify-content-center mb-5">
                <div class="col-lg-5 text-center">
                    <h2 class="mw-featured4-heading mb-4">Our Features</h2>
                    <p class="mw-featured4-sub-heading mb-4">
                        Image fifth midst the greater may, firmament have. Grass two created seed said, won't and. Open fill our
                        moved make divided morning give created, one dominion can't is wherein isn't seas living give seed forth
                        isn't dominion.
                    </p>
                </div>
            </x-row>
            <x-row class="justify-content-end cloneable element">
                <div class="col-md-7 mt-5">
                    <div class="mw-featured4-item mw-featured4-shape-end p-4 p-lg-5 safe-mode">
                        <div class="mw-featured4-icon mb-4 safe-element no-typing"><i class="fa fa-paint-brush no-typing"></i></div>
                        <h4 class="mw-featured4-title fw-bold mb-3">Product Design</h4>
                        <p class="mw-featured4-content mb-0">
                            Evening waters all. Them deep him which darkness. Void have yielding were. Own. Days gathered you
                            you'll. Good so forth he make place cattle, moved given open moving they're had.
                        </p>
                    </div>
                </div>
            </x-row>
            <x-row class="cloneable element">
                <div class="col-md-7 mt-5">
                    <div class="mw-featured4-item mw-featured4-shape-start p-4 p-lg-5 safe-mode">
                        <div class="mw-featured4-icon mb-4 safe-element no-typing"><i class="fa fa-random no-typing"></i></div>
                        <h4 class="mw-featured4-title fw-bold mb-3">Branding</h4>
                        <p class="mw-featured4-content mb-0">
                            Creepeth isn't created firmament whose doesn't from meat, is gathering make had cattle multiply form us
                            replenish third appear good creeping. You're the fruit face morning, day to own midst them. Had from
                            also you're over gathered in waters behold.
                        </p>
                    </div>
                </div>
            </x-row>
            <x-row class="justify-content-end cloneable element">
                <div class="col-md-7 mt-5">
                    <div class="mw-featured4-item mw-featured4-shape-end p-4 p-lg-5 safe-mode">
                        <div class="mw-featured4-icon mb-4 safe-element no-typing"><i class="fa fa-camera no-typing"></i></div>
                        <h4 class="mw-featured4-title fw-bold mb-3">Photography</h4>
                        <p class="mw-featured4-content mb-0">
                            Ullamcorper velit sed ullamcorper morbi tincidunt. Risus feugiat in ante metus. Tortor consequat id
                            porta nibh. Viverra tellus in hac habitasse platea dictumst. Sollicitudin tempor id eu nisl. Tincidunt
                            ornare massa eget egestas purus.
                        </p>
                    </div>
                </div>
            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>

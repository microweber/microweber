{{--
type: layout
name: Design 19
position: 119
categories: Design
--}}

<style>
    .mw-new-19-heading-one {
        color: #222;
        font-size: 64px;
        font-weight: 400;
        letter-spacing: -.02em;
        line-height: 1em;
    }

    .mw-new-19-heading-four {
        font-size: 23px;
        line-height: 1.3em;
    }

    .mw-new-19-heading-four, .text-bold {
        font-variation-settings: "wght" 600;
    }

    .mw-new-19-metric-box, .mw-new-19-quote-box {
        align-items: flex-start;
        background-color: #f4f3f1;
        border-radius: 10px;
        column-gap: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 250px;
        padding: 18px;
        row-gap: 24px;
    }

    .mw-new-19-quote-box {
        background-color: #222;
        color: #fff;
        min-height: 300px !important;
    }

    @media screen and (max-width: 991px) {
        .mw-new-19-heading-one {
            font-size: 58px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-new-19-heading-one {
            font-size: 54px;
        }

        .mw-new-19-heading-four {
            font-size: 21px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-19-heading-one {
            font-size: 39px;
            line-height: 1.15em;
        }

        .mw-new-19-heading-four {
            font-size: 18px;
        }

        .mw-new-19-metric-box, .mw-new-19-quote-box {
            border-radius: 6px;
            min-height: 200px;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-19"
    field-name="layout-new-layouts-skin-19"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row class="justify-content-center align-items-center gap-md-0 gap-3">
                <div class="col-md-3 col-sm-10 mx-auto">
                    <div class="mw-new-19-metric-box background-color-element element">
                        <div data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-19-heading-one">2014</div>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Launched in 2014, This is globally distributed team spans over 80 cities around the world.</p>
                    </div>
                </div>

                <div class="col-md-6 col-sm-10 mx-auto">
                    <div class="mw-new-19-metric-box mw-new-19-quote-box background-color-element element mw-layout-dark-background">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-19-heading-four">“This was designed to demystify legal procedures, offering a streamlined, on-demand legal service platform.”</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-bold">Sam Callahan, Founder &amp; CEO</p>
                    </div>
                </div>

                <div class="col-md-3 col-sm-10 mx-auto">
                    <div class="mw-new-19-metric-box background-color-element element">
                        <div data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-19-heading-one">8,000</div>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">This innovative legal platform is the backbone for over 8,000 businesses in diverse sectors.</p>
                    </div>
                </div>
            </x-row>
</x-layout-section>

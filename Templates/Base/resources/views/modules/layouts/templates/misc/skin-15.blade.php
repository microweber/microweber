{{--
type: layout

name: Misc 15

position: 15

categories: Misc
--}}

<style>
    .misc-15 {
        position: relative;
    }

    .misc-15 .accordion {
        margin-top: -40px;
        margin-bottom: -40px;
        background-color: #7a6ad8;
        border-radius: 40px;
        padding: 80px 50px 50px 50px;
    }

    .misc-15 .accordion-item {
        background-color: #fff;
        border-radius: 40px !important;
        margin-bottom: 30px;
        border: none;
    }

    .misc-15 .accordion-item .accordion-button {
        outline: none;
        box-shadow: none;
        border-radius: 40px !important;
    }

    .misc-15 .accordion-button:not(.collapsed) {
        color: #7a6ad8;
        background-color: #fff;
    }

    .misc-15 h2.accordion-header button {
        padding: 15px 25px;
        font-size: 16px;
        font-weight: 600;
    }

    .misc-15 .accordion-button::after {
        font-size: 18px;
        font-weight: 500;
        background-image: none;
        content: '+';
        width: 30px;
        height: 30px;
        display: inline-block;
        text-align: center;
        line-height: 30px;
        border-radius: 50%;
        background-color: #7a6ad8;
        color: #fff;
    }

    .misc-15 .accordion-button:not(.collapsed)::after {
        background-image: none;
        line-height: 32px;
        content: '-';
    }

    .misc-15 .accordion-body {
        padding: 0px 25px 30px 25px;
        font-size: 14px;
        line-height: 28px;
        color: #4a4a4a;
    }

    .misc-15 .misc-15-heading {
        margin-left: 60px;
        margin-bottom: 0px;
    }

    .misc-15 .misc-15-heading .misc-15-main-button {
        margin-top: 50px;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section misc-15"
    field-name="layout-misc-skin-15"
    container-class="mw-layout-container no-element container edit my-5"
>
    <x-row class="gap-3">
                <div class="col-lg-6">
                    <module type="accordion" template="skin-4" />
                </div>
                <div class="col-lg-5 align-self-center mt-lg-0 mt-5">
                    <div class="misc-15-heading mt-lg-0 mt-3">
                        <p style="color: #7A6AD8;" data-mwplaceholder="{{ _e('Enter title here') }}">About Us</p>
                        <h4 class="font-weight-bold" data-mwplaceholder="{{ _e('Enter title here') }}">What makes us the best academy online?</h4>
                        <p data-mwplaceholder="{{ _e('Enter title here') }}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravid risus commodo.</p>
                        <div class="misc-15-main-button">
                            <module type="btn" text="Read More" button_style="btn-primary"/>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

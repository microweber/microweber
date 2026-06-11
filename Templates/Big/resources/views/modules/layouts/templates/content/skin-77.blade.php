{{--
type: layout

name: Content 77

position: 77

categories: Content
--}}

<style>
    .ziza-template-content-1-boxes {
        margin: 30px 20px;
        cursor: pointer;
        border-radius: 30px;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        background-color: #ffffff;
        padding: 40px 30px;
        z-index: 2;
        position: relative;
    }

    .ziza-77-content-right {
        bottom: 70px;
    }

    .ziza-content-77-dot-ornament {
        position: absolute;
        bottom: -215px;
        left: 233px;
        z-index: 0;
    }

    .ziza-content-77-dot-eclipse {
        position: absolute;
        bottom: 55px;
        right: 300px;
        z-index: 1;
        height: 60px;
        width: 120px;
        overflow: hidden;
    }

    .ziza-content-77-dot-eclipse div {
        position: absolute;
        height: 120px;
        width: 120px;
        bottom: 0;
        left: 0;
        border: 8.8px solid #FF007A;
        border-radius: 100px;
    }

    .ziza-content-77-rectangle24 {
        position: absolute;
        top: 112px;
        right: 0;
        width: 75%;
        height: 70%;
        z-index: 1;
        background-color: #F4F9FF;
        border-top-left-radius: 150px;
    }

    .ziza-content-77-rectangle25 {
        position: absolute;
        top: 0;
        left: 100px;
        z-index: 0;
        width: 178px;
        height: 178px;
        background-color: #FFF5DB;
        border-top-left-radius: 100px;
    }

    .content-77-image-background {
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 30px;
    }

    .content-77-image-background.ziza-image-bg-1 {
        background-color: #F1F7FF;
    }

    .content-77-image-background.ziza-image-bg-2 {
        background-color: #FFF2F8;
    }

    .content-77-image-background.ziza-image-bg-3 {
        background-color: #FFF7E3;
    }

    .content-77-image-background.ziza-image-bg-4 {
        background-color: #DEFFEE;
    }

    .ziza-content-77-icons {
       font-size: 74px;
    }
</style>




<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section {{ $layout_classes ?? '' }}"
    field-name="layout-content-skin-77"
    container-class="mw-layout-container container-fluid safe-mode no-element ps-md-0 ps-lg-5 pe-0 edit pt-0"
>
    <x-row>
                <div class=" col-12 d-xl-flex mx-auto position-relative align-items-center justify-content-center">

                    <div class="ziza-content-77-rectangle24"></div>

                    <div class="col-12 col-xl-4 mx-auto pt-5 text-center text-lg-start d-flex align-items-center justify-content-center mb-md-0 mb-5 position-relative">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/ziza/ziza-Group-70.png') }}"
                             class="ziza-content-77-dot-ornament" alt="" />

                        <div class="ziza-content-77-rectangle25"></div>
                        <div class="  safe-mode" style="z-index: 3;">

                            <div class="regular-mode">
                            <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="pb-7">How can we help <br> your Business ?</h2>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">We build readymade websites, mobile applications, <br> and elaborate online business services.</p>

                           </div>
                        </div>
                    </div>


                    <div class="col-12 col-xl-8">
                        <div class="d-flex justify-content-center align-items-center text-center position-relative" >
                            <div class="col-xxl-7 col-md-8 col-12 mx-auto  mt-md-0 mt-2 "  style="z-index: 2;">
                                <div class="col-md-12 d-md-flex justify-content-center flex-wrap">

                                    <div class="ziza-content-77-dot-eclipse">
                                        <div></div>
                                    </div>

                                    <div class="col-xl-6 col-12 safe-mode">
                                        <div class="ziza-template-content-1-boxes d-flex flex-column justify-content-center align-items-center safe-mode cloneable element background-color-element">
                                            <div class="content-77-image-background background-color-element element ziza-image-bg-1">
                                                <i class="mw-micon-Blackboard ziza-content-77-icons" style="color: #2639ED;"></i>
                                            </div>
                                            <div class="regular-mode">
                                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Business Idea Planning</h5>
                                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mt-2 mb-0" style="font-size: 14px;">We present you a proposal and discuss niffty-gritty like</p>
                                            </div>
                                        </div>

                                        <div class="ziza-template-content-1-boxes d-flex flex-column justify-content-center align-items-center safe-mode cloneable element background-color-element">
                                            <div class="content-77-image-background background-color-element element ziza-image-bg-2">
                                                <i class="mw-micon-Code-Window ziza-content-77-icons" style="color: #FF007A;"></i>
                                            </div>
                                            <div class="regular-mode">
                                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Development Website and App</h5>
                                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mt-2 mb-0" style="font-size: 14px;">Communication protocols apart from engagement models</p>
                                            </div>

                                            </div>

                                    </div>
                                    <div class="col-xl-6 col-12 ziza-77-content-right position-xl-relative safe-mode">
                                        <div class="ziza-template-content-1-boxes d-flex flex-column justify-content-center align-items-center safe-mode cloneable element background-color-element">
                                            <div class="content-77-image-background background-color-element element ziza-image-bg-3">
                                                <i class="mw-micon-Wallet-2 ziza-content-77-icons" style="color: #FF9900;"></i>
                                            </div>
                                            <div class="regular-mode">

                                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Financial Planning System</h5>
                                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mt-2 mb-0" style="font-size: 14px;">Protocols apart from aengage models, pricing billing</p>


                                          </div>
                                        </div>
                                        <div class=" ziza-template-content-1-boxes d-flex flex-column justify-content-center align-items-center safe-mode cloneable element background-color-element">
                                            <div class="content-77-image-background background-color-element element ziza-image-bg-4">
                                                <i class="mw-micon-Bar-Chart4 ziza-content-77-icons" style="color: #00DA71;"></i>
                                            </div>

                                            <div class="regular-mode">
                                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Market Analysis Project</h5>
                                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mt-2 mb-0" style="font-size: 14px;">Protocols apart from aengage models, pricing billing</p>


                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>

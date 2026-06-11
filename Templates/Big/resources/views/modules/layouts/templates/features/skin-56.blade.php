{{--
 type: layout
 name: Feature 56
 position: 56
 categories: Features
--}}

<style>
    .mw-feature-56-services {
        border-top: 1px solid #ececec;
        border-bottom: 1px solid #ececec;
    }

    .mw-features-56-services-thumb {
        background: #fff;
        border: 2px solid transparent;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        padding: 40px 40px 240px 40px;
        transition: all 0.5s;
    }

    .mw-features-56-services-thumb-up {
        position: relative;
        bottom: 50px;
        margin-bottom: -50px;
    }

    .mw-features-56-services-thumb:hover {
        border: 2px solid var(--mw-primary-color);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
    }

    .mw-features-56-services-thumb:hover .mw-feature-56-services-icon-wrap {
        background: var(--mw-primary-color);
        border-color: var(--mw-primary-color);
        color: #fff;
    }

    .mw-feature-56-services-icon-wrap {
        border: 1px solid #ececec;
        border-radius: 10px;
        position: absolute;
        bottom: 0;
        right: 0;
        width: 50%;
        height: 55%;
        transform: rotate(-35deg) translateY(55px);
        transition: all ease 0.5s;
    }

    .mw-feature-56-services-icon {
        font-size: 90px;
        position: relative;
        bottom: 15px;
    }

    .mw-features-56-services-thumb:hover .services-price-overlay {
        background-color: var(--mw-primary-color);
    }

    .mw-feature-56-section-title-wrap {
        background-color: var(--mw-primary-color);
        border-radius: 10px;
        padding: 10px 30px;
    }

    .mw-features-56-avatar-image-wrapper img {
        border-radius: 100px;
        width: 160px;
        height: 160px;
        object-fit: cover;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-feature-56-services feature-56"
    background-attrs='data-background-color="#F9F9F9"'
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
        <div class="container mw-layout-container no-element container edit" field="layout-feature-skin-56-{{ $params['id'] }}" rel="module">
            <x-row>
                <div class="col-lg-10 col-12 mx-auto">
                    <div class="mw-feature-56-section-title-wrap background-color-element element mw-features-56-avatar-image-wrapper d-flex justify-content-center align-items-center mb-5">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/handshake-man-woman-after-signing-business-contract-closeup.jpg') }}" alt=""/>

                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-white ms-4 mb-0">Services</h2>
                    </div>

                    <x-row class="pt-lg-5">
                        <div class="col-lg-6 col-12">
                            <div class="mw-features-56-services-thumb background-color-element element">
                                <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">Websites</h3>
                                </div>

                                <p data-mwplaceholder="{{ _e('Enter text here') }}">You may want to explore Too CSS for great collection of free HTML CSS templates.</p>

                                <div class="mw-feature-56-services-icon-wrap d-flex justify-content-center align-items-center">
                                    <i class="mw-feature-56-services-icon mw-micon-Globe"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="mw-features-56-services-thumb background-color-element element mw-features-56-services-thumb-up">
                                <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">Branding</h3>
                                </div>

                                <p data-mwplaceholder="{{ _e('Enter text here') }}">You can explore more CSS templates on TemplateMo website by browsing through different tags.</p>

                                <div class="mw-feature-56-services-icon-wrap d-flex justify-content-center align-items-center">
                                    <i class="mw-feature-56-services-icon mw-micon-Light-Bulb"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="mw-features-56-services-thumb background-color-element element">
                                <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">Ecommerce</h3>
                                </div>

                                <p data-mwplaceholder="{{ _e('Enter text here') }}">If you need a customized ecommerce website for your business, feel free to discuss with me.</p>

                                <div class="mw-feature-56-services-icon-wrap d-flex justify-content-center align-items-center">
                                    <i class="mw-feature-56-services-icon mw-micon-Smartphone-3"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-12">
                            <div class="mw-features-56-services-thumb background-color-element element mw-features-56-services-thumb-up">
                                <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">SEO</h3>
                                </div>

                                <p data-mwplaceholder="{{ _e('Enter text here') }}">To list your website first on any search engine, we will work together. First Portfolio is one-page CSS Template for free download.</p>

                                <div class="mw-feature-56-services-icon-wrap d-flex justify-content-center align-items-center">
                                    <i class="mw-feature-56-services-icon mdi mdi-google"></i>
                                </div>
                            </div>
                        </div>
                    </x-row>
                </div>
            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>

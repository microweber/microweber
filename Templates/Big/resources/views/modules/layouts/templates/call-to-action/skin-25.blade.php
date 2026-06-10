{{--
type: layout
name: Call to action 25
position: 25
categories: Call to Action
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .merry-cta-div-form .mb-3.d-flex.d-flex {
        margin-bottom: 0!important;
    }

    .merry-cta-div-form {
        padding: 10px;
        background-color: white;
    }

    .merry-cta-circle-images.merry-cta-circle-images-1 {
        bottom: 300px;
        left: -43%;
    }

    .merry-cta-circle-images.merry-cta-circle-images-2 {
        bottom: 170px;
        left: -43%;
    }
    .merry-cta-circle-images.merry-cta-circle-images-3 {
        bottom: 150px;
        left: -27%;
    }
    .merry-cta-circle-images.merry-cta-circle-images-4 {
        bottom: 0px;
        left: -40%;
    }
    .merry-cta-circle-images.merry-cta-circle-images-5 {
        bottom: 300px;
        right: -43%;
    }
    .merry-cta-circle-images.merry-cta-circle-images-6 {
        bottom: 170px;
        right: -25%;
    }
    .merry-cta-circle-images.merry-cta-circle-images-7 {
        top: -128px;
        right: -40%;
    }
</style>

<section class="section {{ $layout_classes }}">
    <module type="background" data-background-color="#ceabb1" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-call-to-action-skin-25-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto text-center">
                <div class="regular-mode">
                    <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-5">New episodes Will always <br> Updated regularly</h1>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-5">Waste of resources our competitors are jumping the shark for to
                        <br> be inspired is to something else.</p>
                </div>
                <br/>
                <br/>
                <module class="w-100" type="contact_form" template="subscribe-6"/>

                <br/>
                <br/>
                <br/>

                <div class="d-flex flex-wrap justify-content-center text-center text-md-start position-relative">
                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-1 position-xl-absolute">
                        <div class="mx-auto" style="width: 95px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-1.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-2 position-xl-absolute">
                        <div class="mx-auto" style="width: 62px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-2.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-3 position-xl-absolute">
                        <div class="mx-auto" style="width: 94px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-3.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-4 position-xl-absolute">
                        <div class="mx-auto" style="width: 94px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-4.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-5 position-xl-absolute">
                        <div class="mx-auto" style="width: 137px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-5.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-6 position-xl-absolute">
                        <div class="mx-auto" style="width: 93px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-6.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 mx-5 cloneable merry-cta-circle-images merry-cta-circle-images-7 position-xl-absolute">
                        <div class="mx-auto" style="width: 114px;">
                            <div class="img-as-background rounded-circle square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/merry/merry-cta-7.png') }}" alt=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

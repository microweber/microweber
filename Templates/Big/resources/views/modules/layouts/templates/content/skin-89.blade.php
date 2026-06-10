@php
    /*

    type: layout

    name: Content 89

    position: 89

    categories: Content

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = '';
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = '';
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .content-89 {
        border-bottom: 1px solid #eee;
    }

    .content-89 .left-image {
        margin-right: 60px;
    }

    .content-89 .section-heading {
        margin-bottom: 40px;
    }

    .content-89 .info-item {
        background-color: #f7f7f7;
        border-radius: 10px;
        padding: 20px;
    }

    .content-89 .info-item span {
        color: var(--mw-primary-color);
    }

    .content-89 .main-button {
        margin-top: 30px;
    }

    .content-89 .main-button a:hover {
        background-color: var(--mw-primary-color);
        border-color: var(--mw-primary-color);
        color: #fff;
        opacity: 0.85;
    }

    .info-item-p {
        color: var(--mw-primary-color);
        font-size: 14px;
    }
</style>

<section class="section content-89">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container mw-layout-container safe-mode mw-layout-overlay-container {{ $layout_classes }} edit" field="layout-content-skin-89-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-6 align-self-center">
                <div class="left-image">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}" alt=""/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-heading mt-5">
                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Discover More About Our Country</h4>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
                </div>
                <div class="row">
                    <div class="col-sm-6 cloneable element safe-mode">
                        <div class="info-item background-color-element element">
                            <h5 class="mb-0">150.640 +</h5>
                            <p class="mb-0 info-item-p">Total Guests Yearly</p>
                        </div>
                    </div>
                    <div class="col-sm-6 cloneable element safe-mode">
                        <div class="info-item background-color-element element">
                            <h5 class="mb-0">175.000+</h5>
                            <p class="mb-0 info-item-p">Amazing Accomoditations</p>
                        </div>
                    </div>
                    <div class="col-lg-12 cloneable element safe-mode">
                        <div class="info-item background-color-element element">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="mb-0">12.560+</h5>
                                    <p class="mb-0 info-item-p">Amazing Places</p>
                                </div>
                                <div class="col-lg-6">
                                    <h5 class="mb-0">240.580+</h5>
                                    <p class="mb-0 info-item-p">Different Check-ins Yearly</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p style="color: #afafaf; margin-top: 15px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
                <div class="main-button mt-5">
                    <module type="btn" button_style="btn btn-primary" button_text="Discover More" />
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

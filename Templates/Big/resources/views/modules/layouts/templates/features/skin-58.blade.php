{{--
 type: layout
 name: Feature 58
 position: 58
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .feature-58 {
        border-bottom: 1px solid #eee;
    }

    .feature-58 .feature-58-item {
        margin: 0px 15px;
        position: relative;
        height: 500px;
    }

    .feature-58 .feature-58-item .img-wrapper {
        height: 100%;
        overflow: hidden;
        position: relative;
    }

    .feature-58 .feature-58-item img {
        border-radius: 23px;
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
        padding-right: 20%;
    }

    .feature-58 .feature-58-item .text {
        position: absolute;
        background-color: #fff;
        border-radius: 23px;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.15);
        padding: 30px;
    }

    .feature-58 .feature-58-item .text h6 {
        display: inline-block;
        float: right;
        text-align: right;
        font-size: 20px;
        color: var(--mw-primary-color);
    }

    .feature-58 .feature-58-item .text ul {
        border-top: 1px solid #eee;
        margin-top: 25px;
        padding-top: 25px;
    }

    .feature-58 .feature-58-item .text ul li {
        color: #afafaf;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .feature-58 .feature-58-item .text ul li:first-child {
        color: #2a2a2a;
        font-weight: 600;
    }

    .feature-58 .feature-58-item .text ul li i {
        color: var(--mw-primary-color);
        margin-right: 10px;
    }

    .feature-58 ul {
        list-style: none;
        padding: 0;
    }
</style>

<section class="section feature-58 {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container no-element edit" field="layout-feature-skin-58-{{ $params['id'] }}" rel="module">
        <div class="row align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading text-center">
                            <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Best Weekly Offers In Each City</h2>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-7">
                <div class="row">
                    <div class="col-xxl-4 col-lg-6 col-12 cloneable element">
                        <div class="feature-58-item">
                            <div class="img-wrapper">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                            </div>

                            <div class="text">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="d-inline-block mb-0">Havana</h5>
                                        <br>
                                        <span style="color: #afafaf;">
                                            <i class="mw-micon-Add-UserStar me-1" style="font-size: 18px;"></i>
                                            234 Check Ins
                                        </span>
                                    </div>

                                    <h6>$420<br><span style="color: #afafaf; font-size: 14px;">/person</span></h6>
                                </div>

                                <ul>
                                    <li>Deal Includes:</li>
                                    <li><i class="fa fa-taxi"></i> 5 Days Trip > Hotel Included</li>
                                    <li><i class="fa fa-plane"></i> Airplane Bill Included</li>
                                    <li><i class="fa fa-building"></i> Daily Places Visit</li>
                                </ul>
                                <div class="main-button mt-4">
                                    <module type="btn" button_style="btn-primary" button_text="Make A Reservation"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-12 cloneable element">
                        <div class="feature-58-item">
                            <div class="img-wrapper">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                            </div>

                            <div class="text">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="d-inline-block mb-0">Havana</h5>
                                        <br>
                                        <span style="color: #afafaf;">
                                            <i class="mw-micon-Add-UserStar me-1" style="font-size: 18px;"></i>
                                            234 Check Ins
                                        </span>
                                    </div>

                                    <h6>$420<br><span style="color: #afafaf; font-size: 14px;">/person</span></h6>
                                </div>

                                <ul>
                                    <li>Deal Includes:</li>
                                    <li><i class="fa fa-taxi"></i> 5 Days Trip > Hotel Included</li>
                                    <li><i class="fa fa-plane"></i> Airplane Bill Included</li>
                                    <li><i class="fa fa-building"></i> Daily Places Visit</li>
                                </ul>
                                <div class="main-button mt-4">
                                    <module type="btn" button_style="btn-primary" button_text="Make A Reservation"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-6 col-12 cloneable element">
                        <div class="feature-58-item">
                            <div class="img-wrapper">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                            </div>

                            <div class="text">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="d-inline-block mb-0">Havana</h5>
                                        <br>
                                        <span style="color: #afafaf;">
                                            <i class="mw-micon-Add-UserStar me-1" style="font-size: 18px;"></i>
                                            234 Check Ins
                                        </span>
                                    </div>

                                    <h6>$420<br><span style="color: #afafaf; font-size: 14px;">/person</span></h6>
                                </div>

                                <ul>
                                    <li>Deal Includes:</li>
                                    <li><i class="fa fa-taxi"></i> 5 Days Trip > Hotel Included</li>
                                    <li><i class="fa fa-plane"></i> Airplane Bill Included</li>
                                    <li><i class="fa fa-building"></i> Daily Places Visit</li>
                                </ul>
                                <div class="main-button mt-4">
                                    <module type="btn" button_style="btn-primary" button_text="Make A Reservation"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

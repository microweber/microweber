@php
/*
type: layout
name: Header 39
position: 39
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    @media screen and (min-width: 1001px) {
        .reservation-info {
            position: absolute;
            left: 50%;
            transform: translate(-50%, 0);
            margin-top: -70px;
        }
        .header-39 {
            margin-bottom: 120px;
        }
    }

    .header-39 .header-39-title {
        font-weight: 500;
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 25px;
    }

    .header-39 .header-39-title::after {
        position: absolute;
        width: 100px;
        height: 2px;
        background-color: rgba(250, 250, 250, 0.3);
        content: '';
        left: 50%;
        bottom: 0;
        transform: translateX(-50px);
    }

    .header-39 .info-item {
        background-color: #fff;
        border-radius: 23px;
        box-shadow: 0px 0px 15px rgba(0,0,0,0.15);
        text-align: center;
        padding: 30px;
    }

    .header-39 .info-item i {
        background-color: #f0f0f0;
        width: 60px;
        height: 60px;
        display: inline-block;
        text-align: center;
        line-height: 60px;
        border-radius: 50px;
        color: var(--mw-primary-color);
        font-size: 20px;
        margin-bottom: 20px;
    }

    .header-39 .info-item h4 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .header-39 .info-item a {
        font-size: 15px;
        color: var(--mw-primary-color);
    }

    .header-39 .header-39-image-container {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }
</style>

<section class="section header-39">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />

    <div class="mw-layout-container safe-mode no-element  {{ $layout_classes }} edit" field="layout-header-skin-39-{{ $params['id'] ?? '' }}" rel="module">
        <div class="container mw-layout-dark-background header-39-image-container allow-select">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="header-39-title" data-mwplaceholder="@lang('Enter title here')">Book Prefered Deal Here</h5>
                    <h3 class="mb-4" data-mwplaceholder="@lang('Enter title here')">Make Your Reservation</h3>
                    <p data-mwplaceholder="@lang('Enter text here')">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt uttersi labore et dolore magna aliqua is ipsum suspendisse ultrices gravida</p>

                    <div class="main-button my-5">
                       <module type="btn" button_style="btn btn-secondary" button_text="Discover More" />
                    </div>
                </div>
            </div>
        </div>

        <div class="container row reservation-info mx-auto">
            <div class="col-lg-4 col-sm-6 cloneable element">
                <div class="info-item background-color-element element">
                    <i class="background-color-element element fa fa-phone"></i>
                    <h5 data-mwplaceholder="@lang('Enter title here')">Make a Phone Call</h5>
                    <a>+123 456 789 (0)</a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 cloneable element">
                <div class="info-item background-color-element element">
                    <i class="background-color-element element fa fa-envelope"></i>
                    <h5 data-mwplaceholder="@lang('Enter title here')">Contact Us via Email</h5>
                    <a>company@email.com</a>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 cloneable element">
                <div class="info-item background-color-element element">
                    <i class="background-color-element element fa fa-map-marker"></i>
                    <h5 data-mwplaceholder="@lang('Enter title here')">Visit Our Offices</h5>
                    <a>24th Street North Avenue London, UK</a>
                </div>
            </div>
        </div>
    </div>
</section>
<module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom"/>

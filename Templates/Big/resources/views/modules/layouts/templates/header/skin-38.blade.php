@php
/*
type: layout
name: Header 38
position: 38
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
    .header-38 .content p {
        padding: 0px 5%;
    }

    .blur-bg {
        width: 100%;
        height: 100%;
        background-size: cover;
        filter: blur(8px) brightness(80%);
        position: absolute;
        left: 0;
        top: 0;
        z-index: 1;
        background-repeat: no-repeat;
        background-position: center center;
    }

    .header-38 .content {
        border-radius: 23px;
        text-align: center;
        padding: 120px 60px;
        position: relative;
        overflow: hidden;
        z-index: 2;
    }

    .header-38 .content h2 {
        position: relative;
        z-index: 2;
        font-size: 50px;
        margin-bottom: 25px;
    }

    .header-38 .content .line-dec {
        position: relative;
        z-index: 2;
        width: 100px;
        height: 2px;
        background-color: rgba(250,250,250,0.3);
        margin: 20px auto;
    }

    .header-38 .content h4 {
        position: relative;
        z-index: 2;
        font-size: 20px;
        font-weight: 400;
    }

    .header-38 .content p {
        position: relative;
        z-index: 2;
        padding: 0px 15%;
    }

    .header-38 .content .main-button {
        position: relative;
        z-index: 2;
        margin-top: 50px;
    }
</style>

<section class="section header-38 mw-layout-dark-background">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />

    <div class="container mw-layout-container safe-mode mh-100vh d-flex align-items-center justify-content-center no-element {{ $layout_classes }} edit" field="layout-header-skin-38-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-lg-12">
                <div class="content">
                    <div class="blur-bg" style="background: url({{ asset('templates/big/img/layouts/gallery-1-5.jpg);"></div>
                    <h4 data-mwplaceholder="@lang('Enter title here')">EXPLORE OUR COUNTRY</h4>
                    <div class="line-dec background-color-element element"></div>
                    <h2 data-mwplaceholder="@lang('Enter title here')">Welcome To Caribbean</h2>
                    <p data-mwplaceholder="@lang('Enter text here')">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt uttersi labore et dolore magna aliqua is ipsum suspendisse ultrices gravida</p>

                    <div class="main-button">
                        <module type="btn" button_style="btn btn-secondary" text="Discover More"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

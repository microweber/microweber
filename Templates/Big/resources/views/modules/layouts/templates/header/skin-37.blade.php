@php
/*
type: layout
name: Header 37
position: 37
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
    .date-text, .location-text {
        border: 1px solid #fff;
        color: #fff;
        display: inline-block;
        padding: 10px 20px;
    }

    .text-info {
        color: var(--mw-primary-color);
    }

    .arrow-icon {
        position: relative;
        top: 50px;
    }
    .arrow-icon {
        background-color: #fff;
        border-radius: 100px;
        color: #000;
        font-size: 18px;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        display: inline-block;
        margin: auto;
        transition: all 0.5s;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .arrow-icon:hover {
        background-color: var(--mw-primary-color);
        color: #fff;
        transition: all 0.5s;
        cursor: pointer;
    }

    .highlight-thumb {
        position: relative;
        overflow: hidden;
        margin-top: 24px;
        margin-bottom: 24px;
    }

    .highlight-thumb::after {
        content: "";
        background: rgba(0, 0, 0, 0.45);
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .highlight-thumb:hover .highlight-title {
        opacity: 0;
        visibility: hidden;
    }

    .highlight-thumb:hover .highlight-icon {
        opacity: 1;
        visibility: visible;
        color: red;
    }

    .highlight-thumb:hover .highlight-image {
        transform: scale(1.2);
    }

    .highlight-info {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        text-align: center;
    }

    .highlight-image {
        display: block;
        width: 100%;
        transition: transform 2s;
    }

    .highlight-title {
        color: #fff;
        transition: opacity 1s;
        margin-bottom: 0;
    }

    .highlight-icon {
        color: var(--highlight-icon-color);
        font-size: var(--h1-font-size);
        opacity: 0;
        transition: transform 1s;
        visibility: hidden;
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .highlight-icon:hover {
        color: #fff;
    }

    .highlight-icon::before {
       font-size: 60px;
    }

    .mw-header-37-bottom-images-wrapper {
        margin-top: 150px;
    }

    .header-37::after {
        content: "";
        background: rgba(0, 0, 0, 0) linear-gradient(rgba(var(--mw-primary-color) 0.1) 0%, rgb(--mw-primary-color) 100%) repeat scroll 0% 0%;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin-bottom: -5px;
    }
</style>

<section class="section header-37 mw-layout-dark-background">
    <module type="background" data-background-video="
    {{ asset('templates/big/video/layouts/content-video-1.mp4') }} " />


    <div class="container mw-layout-container safe-mode mh-100vh d-flex align-items-center justify-content-center no-element {{ $layout_classes }} edit" field="layout-header-skin-37-{{ $params['id'] ?? '' }}" rel="module">
        <div>
            <div class="row regular-mode text-center mt-10">
                <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-4"><u class="text-info">LEADERSHIP</u> CONFERENCE 2024</h1>

                <div class="d-flex justify-content-center align-items-center ">
                    <div class="date-text cloneable element allow-select">July 12 to 18, 2022</div>
                    <div class="location-text cloneable element allow-select">Times Square, NY</div>
                </div>

                <a class="mwiconlist-icon mw-micon-Down arrow-icon"></a>
            </div>

            <div class="row mw-header-37-bottom-images-wrapper">
                <div class="col-lg-4 col-md-6 col-12 cloneable element">
                    <div class="highlight-thumb">
                         <img class="highlight-image" loading="lazy" src="{{ asset('templates/big/img/layouts/events/1.jpg') }}" alt=""/>
                        <div class="highlight-info">
                            <h4 data-mwplaceholder="@lang('Enter title here')" class="highlight-title">2021 Highlights</h4>
                            <a href="https://www.youtube.com/watch?v=3176Sw8A0EE" class="mdi mdi-youtube highlight-icon"></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 cloneable element">
                    <div class="highlight-thumb">
                        <img class="highlight-image" loading="lazy" src="{{ asset('templates/big/img/layouts/events/2.jpg') }}" alt=""/>
                        <div class="highlight-info">
                            <h4 data-mwplaceholder="@lang('Enter title here')" class="highlight-title">2022 Highlights</h4>
                            <a href="https://www.youtube.com/watch?v=3176Sw8A0EE" class="mdi mdi-youtube highlight-icon"></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12 cloneable element">
                    <div class="highlight-thumb">
                        <img class="highlight-image" loading="lazy" src="{{ asset('templates/big/img/layouts/events/3.jpg') }}" alt=""/>
                        <div class="highlight-info">
                            <h4 data-mwplaceholder="@lang('Enter title here')" class="highlight-title">2023 Highlights</h4>
                            <a href="https://www.youtube.com/watch?v=3176Sw8A0EE" class="mdi mdi-youtube highlight-icon"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

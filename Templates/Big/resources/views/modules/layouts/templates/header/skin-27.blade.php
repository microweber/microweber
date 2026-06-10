@php
/*
type: layout
name: Header 27
position: 27
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .photography-orange-rectangle {
        position: absolute;
        width: 92px;
        height: 120px;
        background-color: var(--mw-primary-color);
        z-index: 0;
    }

    .photography-orange-rectangle.photography-rectangle-bottom {
        bottom: -10px;
        left: -20px;
    }

    .photography-orange-rectangle.photography-rectangle-top {
        top: -10px;
        right: -20px;
    }

    .photography-content-27-bottom-box {
        background-color: #ffffff;
        top: 100px;
        z-index: 1;
        box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }
</style>

<section class="section py-0 {{ $layout_classes }}">
    <module type="background" data-background-color="#002632"/>

    <div class="mw-layout-container py-4 no-element d-flex align-items-center mw-big-skin-3-background no-element edit " field="layout-header-skin-27-{{ $params['id'] ?? '' }}" rel="module">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 safe-mode col-xl-10 mx-auto">
                    <div class="row d-flex align-items-center justify-content-center allow-select">
                        <div class="col-12 safe-mode col-lg-6 py-4">
                            <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title" style="color: #ffffff; font-size: 96px; line-height: 106px;">Photography Helps People To See</h1>
                            <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p my-8" style="color: #ffffff; line-height: 40px;">From inspiring people's stories to impactful messages, I create head - turning photograph that does the right things, in the right place , at the right time to unlock the possibility</p>


                                <module type="btn" class="cloneable" button_text="Lets Work With Me" button_style="btn btn-primary px-5 py-4"/>

                        </div>

                        <div class="ms-md-0 ms-md-5 col-10 col-sm-10 col-md-8 col-xl-5 py-4 cloneable position-relative">
                            <div class="photography-orange-rectangle photography-rectangle-top"></div>
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/photography/photography-Rectangle-5285.png') }}" class="w-100" style="position: relative; z-index: 1;" alt=""/>
                            <div class="photography-orange-rectangle photography-rectangle-bottom"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="allow-select col-xl-10 col-12 safe-mode photography-content-27-bottom-box p-5 mx-auto text-center d-flex justify-content-center align-items-center position-relative flex-wrap">
                    <div class="col-lg-3 col-sm-6 col-12 safe-mode cloneable element">
                        <h1>11+</h1>
                        <p style="color: #8F8BA5;">Years Of Experienced</p>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 cloneable element">
                        <h1>250+</h1>
                        <p style="color: #8F8BA5;">Complete Project</p>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 cloneable element">
                        <h1>235+</h1>
                        <p style="color: #8F8BA5;">Happy Customers</p>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12 cloneable element">
                        <h1>12+</h1>
                        <p style="color: #8F8BA5;">Country Visited</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>

@php
/*
type: layout
name: Header 32
position: 32
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'mw-p-t-100';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'mw-p-b-0';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section section-7 d-flex {{ $layout_classes }}">
    <module type="background" data-background-color="#F3F3F3" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

    <div class="container mw-layout-container d-flex edit safe-mode " field="layout-header-skin-32-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-lg-6 col-lg-6 allow-drop align-self-center allow-select">
                <h4 data-mwplaceholder="@lang('Enter title here')" class="header-section-title">
                    Cosmetic beauty Clinic "Darlla"
                </h4>

                <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mt-3">
                    Beauty is our mision and we will help you achieve the desired
                </p>
                <div class="mt-5">
                    <module type="btn" class="allow-drop" button_text="Book a Consultation"/>
                </div>
            </div>

            <div class="col-lg-6 col-lg-6 text-center allow-drop align-self-end allow-select">
                <img loading="lazy" src="{{ asset('templates/big/img/sections/home.jpg') }}" alt=""/>


            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>

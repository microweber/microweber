@php
/*
type: layout
name: Header 25 - Parallax
position: 25
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'mw-p-t-10';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'mw-p-b-100';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-header-25 {
        position: absolute;
        bottom: 100px;
        left: 50px;
    }

    @media (max-width: 600px) {
        .mw-header-25 {
            left: 10px;
            bottom: 150px;
        }
    }
</style>

<section class="section py-0 mw-layout-parallax guesthouse-header d-flex align-items-center justify-content-center">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/main-home.jpg') }}" class="mw-header-section-mh-100vh" style="min-height: calc(100vh - 70px)"/>
    <div class="container mw-layout-container mw-header-25  edit" field="layout-header-skin-24-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row">
            <div class="col-12 safe-mode">
                <div class="allow-select info-holder">
                    <h2 data-mwplaceholder="@lang('Enter title here')" class="fx-deactivate">Welcome to our <strong>Cliff House</strong></h2>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p">The heart of the mountain</p>
                </div>
            </div>
        </div>
    </div>
</section>

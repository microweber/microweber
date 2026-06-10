@php
/*
type: layout
name: Header 34 Shape Full
position: 21
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} edit safe-mode " field="layout-header-skin-34-full-{{ $params['id'] ?? '' }}" rel="module" data-background-position="center center" data-overlay="1" style="background-color: #ffffff;">
    <div class="h-100 w-100 overflow-hidden position-absolute top-0 right-0">
        <div style="background-image: url({{ asset('templates/big/img/layouts/gallery-1-3.jpg)" class="header-34-mask"/>
    </div>
    <div class="mw-header-section-mh-100vh d-flex align-items-center background-image-holder">
        <div class="container header-34-header d-flex align-items-center mw-header-section-mh-100vh">
            <div class="row flex-lg-nowrap col-12 ms-0">
                <div class="col-12 col-xs-10 col-lg-6 pb-5 mb-4 pe-lg-5 d-flex align-items-center justify-content-center justify-content-lg-start z-index-header-34-text">
                    <div class="allow-drop text-white regular-mode">
                        <h1 class="header-h1-text"><span style="color: #100f43;">Invest in</span> <span style="color:#ffae00;">Crypto</span> <br> <span style="color: #100f43;">Currencies</span> </h1>
                        <br>
                        <p class="header-h1-paragraph">Use modern blockchain technologies and Bitcoin to earn money.</p>
                        <br/>
                        <br/>
                        <module type="btn" btn_text="Create Wallet"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

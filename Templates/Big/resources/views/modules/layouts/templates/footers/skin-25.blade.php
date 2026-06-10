{{--
type: layout
name: Footers 25
position: 25
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .footer-skin-25 .white-color {
        color: #a3a3a3;
    }
</style>

<section class="footer-skin-25 footer-background py-0" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <!-- Footer -->
    <div class="mw-layout-container no-element container-fluid edit" field="layout-footer-skin-25-{{ $params['id'] }}" rel="module">
        <div class="row justify-content-center">
            <div class="col-md-12 d-md-flex justify-content-md-center align-items-lg-center mt-lg-7">
                <div class="col-md-4 col text-md-start text-center">
                    <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                </div>
                <div class="col-md-4 col">
                    <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
                </div>
                <div class="col-md-4 col text-md-end text-center">
                    <module type="social_links"/>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container-fluid py-2">
        <div class="row">
            <div class="col-12 d-sm-flex text-center">
                <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-25-{{ $params['id'] }}" rel="module">
                    <small>
                        © All Rights Reserved.
                    </small>
                </div>
                <div class="col-sm-6 mb-0 noedit text-md-end text-center">
                    <small>
                        {!! powered_by_link() !!}
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

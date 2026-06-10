{{--
type: layout
name: Footers 31
position: 31
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .footer-skin-31 .white-color {
        color: #a3a3a3;
    }

    @media screen and (min-width: 1000px) {
        .footer-skin-link ul {
            justify-content: start;
        }
    }
</style>

<section class="footer-skin-31 footer-background py-0" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <!-- Footer -->
    <div class="mw-layout-container no-element container edit" field="layout-footer-skin-31-{{ $params['id'] }}" rel="module">
        <div class="row justify-content-center">
            <div class="col-md-12 d-md-flex border-bottom justify-content-md-center align-items-lg-center pt-lg-3 pb-5 mb-4">
                <div class="col-md-6 col text-md-start text-center">
                    <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                </div>
                <div class="col-md-6 col text-md-end text-center d-flex justify-content-md-end justify-content-center my-md-0 my-3">
                    <module type="social_links"/>
                </div>
            </div>
        </div>
    </div>

    <div class="mw-layout-container no-element container py-2 mb-3">
        <div class="row justify-content-between">
            <div class="col-lg-6 col-12 my-lg-0 my-3">
                <module type="menu" class="footer-skin-link text-lg-start text-center" id="footer-menu-skin-31-{{ $params['id'] }}" template="simple" name="footer_menu"/>
            </div>

            <div class="col-lg-6 col-12 text-lg-end text-center">
                <div class="edit safe-mode mb-4" field="footer-reserved-skin-31-{{ $params['id'] }}" rel="module">
                    <small>
                        © All Rights Reserved.
                    </small>
                </div>
                <div class="mb-0 noedit">
                    <small>
                        {!! powered_by_link() !!}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

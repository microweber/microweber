{{--
type: layout
name: Footers 32
position: 32
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .footer-19-menu ul li a:first-child{
        padding-left: 0;
    }

    .footer-19-menu ul{
        display: flex;
        flex-wrap: wrap;
    }
</style>

<div class="footer-background py-0 {{ $layout_classes }}" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-footer-skin-32-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="edit mb-7 text-center" field="layout-footer-skin-19-title-{{ $params['id'] }}" rel="module">
                <h2 class="font-weight-bold">You have an idea? Let's Talk!</h2>
            </div>

            <div class="col-lg-5 text-md-start text-md-left">
                <div class="edit" field="layout-footer-skin-19-phone{{ $params['id'] }}" rel="module">
                    <small> Phone </small>
                    <p class="mt-2">123-456-7890</p>
                </div>
                <div class="edit mb-5" field="layout-footer-skin-19-email{{ $params['id'] }}" rel="module">
                    <small> Email </small>
                    <p class="mt-2"><a href="">mail@yourcompany.com</a></p>
                </div>
                <div class="edit" field="layout-footer-skin-19-social{{ $params['id'] }}" rel="module">
                    <module type="social_links" template="skin-9"/>
                </div>
            </div>

            <div class="col-lg-7 mt-lg-0 mt-5 row">
                <div class="col-md-6 me-auto d-flex justify-content-start">
                    <module type="menu" class="text-center" template="simple" name="footer_menu"/>
                </div>

                <div class="col-md-6 mt-md-0 mt-4">
                    <module type="contact_form" template="skin-3"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container-fluid py-2">
        <div class="row">
            <div class="col-12 d-sm-flex text-center">
                <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-19-{{ $params['id'] }}" rel="module">
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
</div>

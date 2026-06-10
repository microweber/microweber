{{--
type: layout
name: Footers 33
position: 33
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .footer-33 .icon {
        margin-right: 15px;
        font-size: 24px;
        line-height: 0;
        margin-top: 23px;
    }

    .footer-33 h4 {
        font-weight: bold;
        position: relative;
        padding-bottom: 5px;
    }

    .footer-33 .footer-links {
        margin-bottom: 30px;
    }
</style>

<div class="footer-background footer-33 py-0 {{ $layout_classes }}" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-footer-skin-33-{{ $params['id'] }}" rel="module">
        <div class="row gy-3">
            <div class="col-lg-3 col-md-6 d-flex justify-content-center">
                <i class="mw-micon-Location-2 icon"></i>
                <div>
                    <h4>Address</h4>
                    <p>
                        A108 Adam Street <br>
                        New York, NY 535022 - US<br>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 footer-links d-flex justify-content-center">
                <i class="mw-micon-Telephone icon"></i>
                <div>
                    <h4>Reservations</h4>
                    <p>
                        <strong>Phone:</strong> +1 5589 55488 55<br>
                        <strong>Email:</strong> info@example.com<br>
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 footer-links d-flex justify-content-center">
                <i class="mw-micon-Clock-Forward icon"></i>
                <div>
                    <h4>Opening Hours</h4>
                    <p>
                        <strong>Mon-Sat: 11AM</strong> - 23PM<br>
                        Sunday: Closed
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 footer-links">
                <h4>Follow Us</h4>
                <div class="social-links d-flex">
                    <module type="social_links" template="skin-9"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element footer-33 container-fluid py-2">
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

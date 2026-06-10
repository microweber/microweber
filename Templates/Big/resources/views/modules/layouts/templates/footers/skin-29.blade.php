{{--
type: layout
name: Footers 29
position: 29
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="footer-skin-29 footer-background py-0" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid">
        <div class="d-flex flex-column gap-2 justify-content-center edit" field="layout-footer-skin-29-{{ $params['id'] }}" rel="module">
            <div class="text-center">
                <module type="logo" id="footer-logo-{{ $params['id'] }}" />
            </div>
            <div class="">
                <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
            </div>
            <div class="text-center">
                <module type="social_links"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container-fluid py-2">
        <div class="row">
            <div class="col-12 d-sm-flex text-center">
                <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-29-{{ $params['id'] }}" rel="module">
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

{{--
type: layout
name: Footers 28
position: 28
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="footer-background {{ $layout_classes }}" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <!-- Footer -->
    <div class="mw-layout-container no-element container edit" field="layout-footer-skin-28-{{ $params['id'] }}" rel="module">
        <div class="row justify-content-center">
            <div class="col-md-12 d-md-flex justify-content-md-center align-items-lg-center px-md-0 my-lg-5">
                <div class="col-md-7 col">
                    <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <module type="social_links"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container-fluid py-2">
        <div class="row">
            <div class="col-12 d-sm-flex text-center">
                <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-28-{{ $params['id'] }}" rel="module">
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

{{--
type: layout
name: Footers 1
position: 1
categories: Footers
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="footer-background py-0 {{ $layout_classes }}" id="mw-footer-background">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <!-- Footer -->
    <div class="mw-layout-container no-element container text-md-start text-center edit" field="layout-footer-skin-1-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-md-2 col me-4">
                <div class="pb-7">
                    <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                </div>

                <p class="font-weight-bold">Website Builder and CMS </p>
                <br>
                <small>CMS is a website builder and content management system of new generation.</small>
                <br>
                <module type="social_links"/>
            </div>
            <div class="row col-md-10 row px-md-10">
                <div class="col">
                    <p class="font-weight-bold ms-3"> Footer Menu  </p>
                    <module type="menu" template="simple" class="pb-lg-4" name="footer_menu"/>
                </div>

                <div class="col">
                    <p class="font-weight-bold ms-3"> Footer Menu 1 </p>
                    <module type="menu" template="simple" name="footer_menu"/>
                </div>

                <div class="col">
                    <p class="font-weight-bold ms-3"> Footer Menu 2 </p>
                    <module type="menu" template="simple" name="footer_menu"/>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container-fluid py-2">
        <div class="row">
            <div class="col-12 d-sm-flex text-center">
                <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-1-{{ $params['id'] }}" rel="module">
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

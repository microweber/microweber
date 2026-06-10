{{--
type: layout
name: Footers 27
position: 27
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
    <div class="mw-layout-container no-element container edit" field="layout-footer-skin-27-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 row justify-content-md-center">
                <div class="col-sm-6 col-12 text-md-start text-center">
                    <div class="mb-md-0 mb-5">
                        <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                    </div>
                </div>

                <div class="col-sm-6 col-12 align-self-center">
                    <module type="contact_form" template="subscribe-6"/>
                </div>
            </div>
        </div>
        <div class="text-md-start text-center">
            <div class="d-md-flex align-items-md-center px-md-0 mt-4 pb-4">
                <div class="col-md-4 col">
                    <p class="font-weight-bold">Website Builder and CMS </p>
                    <br>
                    <small>CMS is a website builder and content management system of new generation.</small>
                    <br>
                </div>
                <div class="col-md-4 col">
                    <module type="menu" class="footer_skin" template="simple" name="footer_menu"/>
                </div>
                <div class="col-md-4 col text-md-end">
                    <module type="social_links"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container-fluid py-2">
        <div class="row">
            <div class="col-12 d-sm-flex text-center">
                <div class="col-sm-6 text-md-start text-center edit safe-mode" field="footer-reserved-skin-27-{{ $params['id'] }}" rel="module">
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

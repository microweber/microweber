{{--
type: layout
name: Footers 19
position: 19
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
    <div class="mw-layout-container no-element container edit" field="layout-footer-skin-19-{{ $params['id'] }}" rel="module">
        <div class="row text-md-start text-center">
            <div class="col-lg-5 col text-md-start text-md-left">
                <div class="edit" field="layout-footer-skin-19-p-{{ $params['id'] }}" rel="module">
                    <p class="font-weight-bold">Website Builder and CMS </p>
                    <br>
                    <small>This is a website builder and content management system of new generation.</small>
                    <br>
                </div>
                <module type="menu" class="footer-19-menu d-flex justify-content-lg-start justify-content-center ps-0 mt-3" template="simple" name="footer_menu"/>
            </div>
            <div class="col-lg-4">
                <div class="edit" field="layout-footer-skin-19-phone{{ $params['id'] }}" rel="module">
                    <small> Phone </small>
                    <p class="mt-2">123-456-7890</p>
                </div>
                <div class="edit" field="layout-footer-skin-19-email{{ $params['id'] }}" rel="module">
                    <small> Email </small>
                    <p class="mt-2"><a href="">mail@yourcompany.com</a></p>
                </div>
                <div class="edit" field="layout-footer-skin-19-social{{ $params['id'] }}" rel="module">
                    <p> Social </p>
                    <module type="social_links"/>
                </div>
            </div>
            <div class="col-lg-3 edit" field="layout-footer-skin-19-country{{ $params['id'] }}" rel="module">
                <small> California </small>
                <p class="mt-2"> 21 Lebsack Harbor Apt. 276 Palo Alto, CA </p>
                <small> New York </small>
                <p class="mt-2"> 74 Howell Islands Suite 834 Rochester, NY </p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

    <div class="mw-layout-container no-element container py-4">
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

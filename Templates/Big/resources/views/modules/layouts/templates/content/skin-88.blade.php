@php
    /*

    type: layout

    name: Content 88

    position: 88

    categories: Content

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = 'pt-10';
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = 'pb-10';
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .content-88-box {
        background-color: rgb(var(--mw-primary-color) / .6);
        flex: 1 0 auto;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
    }
</style>

<section class="section mw-layout-dark-background py-0 d-flex align-items-top gap-3 align-items-center justify-content-center" data-parallax-x="true" data-overlay-black="true" data-overlay="2">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container mw-layout-container safe-mode mw-layout-overlay-container {{ $layout_classes }} pt-10 pb-10 edit" field="layout-content-skin-88-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="content-88-box p-md-5 p-3 col-md-6 col-12 cloneable background-color-element element" style="background-color: rgb(var(--mw-primary-color) / .6);">
                <div class="d-flex align-items-top gap-3">
                    <div>
                        <h3>01</h3>
                    </div>
                    <div>
                        <h3>Web Design</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                    </div>
                </div>
            </div>

            <div class="content-88-box p-md-5 p-3 background-color-element col-md-6 col-12 cloneable element">
                <div class="d-flex align-items-top gap-3">
                    <div>
                        <h3>02</h3>
                    </div>
                    <div>
                        <h3>Product Design</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                    </div>
                </div>
            </div>

            <div class="content-88-box p-md-5 p-3 background-color-element col-md-6 col-12 cloneable element">
                <div class="d-flex align-items-top gap-3">
                    <div>
                        <h3>03</h3>
                    </div>
                    <div>
                        <h3>Branding</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                    </div>
                </div>
            </div>

            <div class="content-88-box p-md-5 p-3 background-color-element col-md-6 col-12 cloneable element" style="background-color: rgb(var(--mw-primary-color) / .6);">
                <div class="d-flex align-items-top gap-3">
                    <div>
                        <h3>04</h3>
                    </div>
                    <div>
                        <h3>Graphic and video</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

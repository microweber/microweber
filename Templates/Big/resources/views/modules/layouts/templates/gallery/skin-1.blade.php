{{--
type: layout
name: Gallery 1
position: 1
categories: Gallery
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .gallery-skin-1 img {
        height: 100% !important;
        width: 100% !important;
    }
</style>

<section class="section gallery-skin-1 {{ $layout_classes }}">

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-gallery-skin-1-{{ $params['id'] }}" rel="module">
        <div class="row m-0 no-element">
            <div class="d-flex flex-wrap pe-md-5 pb-5 col-12 col-lg-6 no-element">
                <div class="col-6 pe-2">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt="">
                    </div>
                </div>

                <div class="col-6 ps-2">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt="">
                    </div>
                </div>

                <div class="col-12 pt-5">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap col-12 col-lg-6 pe-md-5 pb-5 no-element">
                <div class="col-12 pb-5">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt="">
                    </div>
                </div>

                <div class="col-6 pe-2">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt="">
                    </div>
                </div>

                <div class="col-6 ps-2">
                    <div class="img-as-background square">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-6.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

{{--
type: layout
name: Gallery 7
position: 7
categories: Gallery
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-gallery-skin-7-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                </div>
                <div class="py-4 mt-md-auto">
                    <h5 class="mb-2">Pictures In The Sky</h5>
                    <p>History of modern astronomy, there is probably no one.</p>
                </div>
            </div>

            <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                </div>
                <div class="py-4 mt-md-auto">
                    <h5 class="mb-2">Radio Astronomy</h5>
                    <p>History of modern astronomy, there is probably no one.</p>
                </div>
            </div>

            <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                </div>
                <div class="py-4 mt-md-auto">
                    <h5 class="mb-2">The Amazing Hubble</h5>
                    <p>History of modern astronomy, there is probably no one.</p>
                </div>
            </div>

            <div class="mx-auto col-sm-5 col-md-3 mb-md-5 cloneable element">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt=""/>
                </div>
                <div class="py-4 mt-md-auto">
                    <h5 class="mb-2">Look Up In The Sky</h5>
                    <p>History of modern astronomy, there is probably no one.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

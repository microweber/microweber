{{--
type: layout
name: Gallery 8
position: 8
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
    <div class="mw-layout-container no-element container edit" field="layout-gallery-skin-8-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="mx-auto col-sm-10 col-md-6 mb-2 cloneable element">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                </div>
            </div>

            <div class="mx-auto col-sm-10 col-md-6 mb-2 cloneable element">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

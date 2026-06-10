{{--
type: layout
name: Gallery 24
position: 24
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
    <div class="mw-layout-container no-element container-fluid p-0 edit" field="layout-gallery-skin-24-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-sm-10 col-md-6 cloneable element p-0">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                </div>
            </div>

            <div class="col-sm-10 col-md-6 cloneable element p-0">
                <div class="img-as-background square">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

{{--
type: layout
name: Gallery 11
position: 11
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
    <div class="mw-layout-container no-element container edit" field="layout-gallery-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="row text-center text-md-start">
                    <div class="mx-auto col-sm-10 col-md-6 mb-md-5 cloneable element">
                        <div class="d-flex flex-column">
                            <div class="img-as-background square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                            </div>
                            <div class="py-4">
                                <p>For most of us, it’s a curiosity, an amusement to see what they say our day will be like based.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mx-auto col-sm-10 col-md-6 mt-0 mt-md-9 mb-md-5 cloneable element">
                        <div class="d-flex flex-column">
                            <div class="img-as-background square">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                            </div>
                            <div class="py-4">
                                <p>Having used discount toner cartridges for twenty years, there have been a lot of changes in the toner.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

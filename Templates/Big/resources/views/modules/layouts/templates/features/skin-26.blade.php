{{--
 type: layout
 name: Feature 26
 position: 26
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-feature-skin-26-{{ $params['id'] }}" rel="module">
        <div class="row text-center text-md-start">
            <div class="mx-auto col-sm-10">
                <div class="row mb-7 cloneable element background-color-element safe-mode">
                    <div class="col-md-6 d-block d-md-flex align-items-center order-1 order-md-2 icon-size-82px">
                        <div class="mx-auto d-flex align-items-center justify-content-md-start justify-content-center mb-sm-0 mb-3">
                            <i class="safe-element no-typing mw-micon-Computer-3"></i>
                        </div>
                    </div>

                    <div class="col-md-6 d-block d-md-flex align-items-center order-2 order-md-1">
                        <div class="regular-mode">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h4>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning of the original Star Trek show really did do a good job of capturing our feelings</p>
                        </div>
                    </div>
                </div>

                <div class="row mb-7 cloneable element background-color-element safe-mode">
                    <div class="col-md-6 d-block d-md-flex align-items-center icon-size-82px">
                        <div class="mx-auto d-flex align-items-center justify-content-md-start justify-content-center mb-sm-0 mb-3">
                            <i class="safe-element no-typing mw-micon-Computer"></i>
                        </div>
                    </div>

                    <div class="col-md-6 d-block d-md-flex align-items-center">
                        <div class="regular-mode">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h4>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning of the original Star Trek show really did do a good job of capturing our feelings</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

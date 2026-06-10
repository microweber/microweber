{{--
 type: layout
 name: Feature 11
 position: 11
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-feature-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row mb-3 py-4 text-center text-sm-start d-flex justify-content-center justify-content-lg-between">
            <div class="col-sm-10 col-md-8 col-lg-6 mb-3 cloneable element background-color-element safe-mode">
                <div class="d-block d-sm-flex align-items-start safe-mode">
                    <div class="col-sm-auto me-xl-3 me-sm-5 me-0 mt-3 icon-size-50px">
                        <i class="safe-element no-typing mw-micon-Alarm"></i>
                    </div>
                    <div class="col-md-10 regular-mode">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Astronomy is one of the oldest sciences of all time. When archeologists unearth ancient civilizations, even as far back as the cavemen, they invariably find art that shows mans unquenchable fascination.</p>
                        <module class="mt-3" type="btn" text="Learn More" button_style="btn-primary" button_size=" "/>
                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-md-8 col-lg-6 mb-3 cloneable element background-color-element safe-mode">
                <div class="d-block d-sm-flex align-items-start">
                    <div class="col-sm-auto me-xl-3 me-sm-5 me-0 mt-3 icon-size-50px">
                        <i class="safe-element no-typing mw-micon-Apple"></i>
                    </div>
                    <div class="col-md-10 regular-mode">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Features Title</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Astronomy is one of the oldest sciences of all time. When archeologists unearth ancient civilizations, even as far back as the cavemen, they invariably find art that shows mans unquenchable fascination.</p>
                        <module class="mt-3" type="btn" text="Learn More" button_style="btn-primary" button_size=" "/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

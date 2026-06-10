{{--
 type: layout
 name: Feature 52
 position: 52
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .feature-52 {
        border-top-right-radius: 500px;
        border-bottom-right-radius: 500px;
    }

    .feature-52 .counter {
        text-align: center;
        margin-bottom: 40px;
    }

    .feature-52 h2 {
        color: #fff;
        font-size: 48px;
        font-weight: 700;
    }

    .feature-52 p {
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        margin-top: 15px;
    }
</style>

<section class="section feature-52 {{ $layout_classes }} ">
    <module type="background" data-background-color="var(--mw-primary-color);" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container no-element container edit" field="layout-feature-skin-52-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 cloneable element">
                            <div class="counter">
                                <h2>150 +</h2>
                                <p class="count-text">Happy Students</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 cloneable element">
                            <div class="counter">
                                <h2>804 +</h2>
                                <p class="count-text">Course Hours</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 cloneable element">
                            <div class="counter">
                                <h2>150 +</h2>
                                <p class="count-text">Employed Students</p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 cloneable element">
                            <div class="counter end">
                                <h2>15 +</h2>
                                <p class="count-text">Years Experience</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

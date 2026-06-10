{{--
 type: layout
 name: Design 2
 position: 126
 categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .mw-new-layouts-2 .mw-layout-new-2-box {
        border-radius: 30px;
        padding: 0;
        position: relative;
    }

    .mw-new-layouts-2 .mw-new-2-shape-wrapper {
        position: relative;
        height: 100px;
        width: 100%;
        top: 0;
        border-top-left-radius: 30px;
        border-top-right-radius: 30px;
        right: 0;
        clip-path: polygon(50% 0%, 100% 0, 100% 50%, 100% 70%, 50% 100%, 0 70%, 0% 50%, 0 0);
        z-index: 2;
    }

    .mw-new-layouts-2 .mw-new-2-services-circle {
        border-radius: 50%;
        padding: 30px;
        position: absolute;
        top: 85px;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        height: 100px;
        width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;

        i:before {
            font-size: 40px;
            color: #fff;
        }
    }

    .mw-new-layouts-2 .mw-new-2-box-padding {
        padding: 50px;
        margin-top: 20px;
    }

    .mw-new-layouts-2 p {
        font-size: 20px;
    }

    .mw-new-layouts-2 .mw-new-2-inside-title {
        font-weight: bold;
    }

    .mw-new-layouts-2 a {
        text-decoration: none;
    }

    .mw-new-layouts-2 .mw-new-2-title {
        font-weight: bold;
    }

    .mw-new-layouts-2 .mw-new-2-button-text {
        font-size: 14px;
        font-weight: bold;
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-2">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element text-center edit safe-mode no-typing"
         field="layout-new-layouts-skin-2-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="d-flex align-items-center justify-content-between mb-5">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-2-title">We build experience</h3>

                <div data-mwplaceholder="{{ _e('Enter text here') }}" class="mw-new-2-button-text">WORK WITH US</div>
            </div>

            <div class="col-xl-4 col-sm-6 element cloneable mb-3">
                <div class="mw-layout-new-2-box background-color-element element" style="background-color: #FAFAFA;">
                    <div class="mw-new-2-shape-wrapper background-color-element element" style="background-color: #FFCBC3;"></div>
                    <div class="mw-new-2-services-circle background-color-element element" style="background-color: #FF705A;">
                        <i class="mw-micon-Idea-2"></i>
                    </div>

                    <div class="mw-new-2-box-padding">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-2-inside-title">Prototyping</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Make sure you're building the right product.</p>
                        <div class="mt-4">
                            <module type="btn" button_style="btn-link" text="LEARN MORE"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 element cloneable mb-3">
                <div class="mw-layout-new-2-box background-color-element element" style="background-color: #FAFAFA;">
                    <div class="mw-new-2-shape-wrapper background-color-element element" style="background-color: #C2D2FD;"></div>
                    <div class="mw-new-2-services-circle background-color-element element" style="background-color: #3C5CCF;">
                        <i class="mw-micon-Shop-2"></i>
                    </div>

                    <div class="mw-new-2-box-padding">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-2-inside-title">Prototyping</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Make sure you're building the right product.</p>
                        <div class="mt-4">
                            <module type="btn" button_style="btn-link" text="LEARN MORE"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-sm-6 element cloneable mb-3">
                <div class="mw-layout-new-2-box background-color-element element" style="background-color: #FAFAFA;">
                    <div class="mw-new-2-shape-wrapper background-color-element element" style="background-color: #A5FFDE;"></div>
                    <div class="mw-new-2-services-circle background-color-element element" style="background-color: #3BCF91;">
                        <i class="mw-micon-Brain-2"></i>
                    </div>

                    <div class="mw-new-2-box-padding">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-2-inside-title">Prototyping</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Make sure you're building the right product.</p>
                        <div class="mt-4">
                            <module type="btn" button_style="btn-link" text="LEARN MORE"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

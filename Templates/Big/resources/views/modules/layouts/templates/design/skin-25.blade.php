{{--
type: layout
name: Design 25
position: 125
categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .flex-right, .flex-tag {
        display: flex;
    }

    .flex-tag {
        align-items: center;
        background-color: var(--mw-primary-color);
        border-color: #c7c3cf;
        border-radius: 30px;
        border-width: 2px;
        column-gap: 10px;
        margin-bottom: 30px;
        padding: 10px 20px;
    }

    .mw-new-25-title-tag {
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.1;
    }

    .mw-new-25-title {
        font-size: 38px;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 5px;
        margin-top: 0;
    }

    @media screen and (max-width: 991px) {
        .mw-new-25-title {
            font-size: 45px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-25-title {
            font-size: 34px;
        }
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-25">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode" field="layout-new-layouts-skin-25-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12">
                <div class="flex-right">
                    <div class="flex-tag background-color-element element" style="opacity: 1;">
                        <span class="mw-new-25-title-tag">TESTIMONIALS</span>
                    </div>
                </div>
                <div class="mb-4">
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-25-title">WHAT CLIENTS ARE
                        <br> SAYING ABOUT US?
                    </h2>
                </div>

                <module type="testimonials" template="skin-22"/>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>

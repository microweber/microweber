@php
/*
type: layout
name: Header 34 - Shape
position: 34
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section py-0" data-background-position="center center" data-overlay-black="true" data-overlay="4">
    <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

    <div class="h-100 w-100 overflow-hidden position-absolute top-0 right-0 edit safe-mode" field="layout-header-skin-34-{{ $params['id'] ?? '' }}" rel="module">
        <div style="background-image: url({{ asset('templates/big/img/layouts/gallery-1-4.jpg') }});" class="header-34-mask">
        </div>
    </div>
    <div class="header-34-header-vh d-flex align-items-center">
        <div class="container header-34-header d-flex align-items-center">
            <div class="row flex-lg-nowrap col-12 ms-0">
                <div class="col-12 col-xs-10 col-lg-6 pe-lg-5 text-center text-lg-start d-flex align-items-center justify-content-center justify-content-lg-start z-index-header-34-text">
                    <div class="allow-drop text-white">
                        <h3>
                            <span style="color: #f2a900;">
                                @if (!is_post())
                                    {{ page_title() }}
                                @endif
                            </span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</section>

{{--
type: layout
name: Grid 11
position: 11
categories: Grids
--}}

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; 
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
if (page_title()) {
    $title = page_title();
}
@endphp

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-11-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-12 mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="cube text-center">
                    <h1 data-mwplaceholder="{{ __('Enter title here') }}">Asteroids</h1>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">When television was young, there was a hugely popular show based on the still<br> popular functional character of Superman. The opening of that show had a familiar<br> phrase that went.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

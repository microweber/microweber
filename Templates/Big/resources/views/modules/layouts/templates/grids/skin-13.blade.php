{{--
type: layout
name: Grid 13
position: 13
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

<section class="section {{ $layout_classes }} layouts-grids-background ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-13-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-8 mb-2 cloneable element safe-mode">
                <div class="cube">
                    <h3 data-mwplaceholder="{{ __('Enter title here') }}">The Amazing Hubble</h3>
                    <p data-mwplaceholder="{{ __('Enter title here') }}">When television was young, there was a hugely popular show based on the still popular functional character of Superman. The opening of that show had a familiar phrase that went.</p>
                </div>
            </div>
            <div class="col-12 col-lg-4 mb-2 cloneable element safe-mode">
                <div class="cube">
                    <h3 data-mwplaceholder="{{ __('Enter title here') }}">Radio Astronomy</h3>
                    <p data-mwplaceholder="{{ __('Enter title here') }}">There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

{{--
type: layout
name: Grid 12
position: 12
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
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-12-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-6 mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="cube">
                    <h3 data-mwplaceholder="{{ __('Enter title here') }}">Look Up In The Sky</h3>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble. While NASA has had many ups and downs.</p>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="cube">
                    <h3 data-mwplaceholder="{{ __('Enter title here') }}">How To Look Up</h3>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble. While NASA has had many ups and downs.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

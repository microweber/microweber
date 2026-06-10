@php
/*
type: layout
name: Header 33 - Slider
position: 33
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section">
    <div class="mw-layout-container no-element edit" field="layout-header-skin-33-{{ $params['id'] ?? '' }}" rel="module">
        <module class="allow-select" type="slider"/>
    </div>
</section>

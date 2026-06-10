{{--
 type: layout
 name: Free Element Fixed Container
 position: 1000
 categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    #mw-free-layout-container-{{ $params['id'] }}{
        min-height: 300px;
        min-height: max(300px, calc(100vh - 500px));
        position:relative;
    }
</style>

<section class="{{ $layout_classes }} section ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <div
        id="mw-free-layout-container-{{ $params['id'] }}"
        class="no-element">
        <div class="mw-layout-container mw-free-layout-container mw-free-layout-container-fixed">
            <div
                class="edit "
                field="layout-content-free-element-container-{{ $params['id'] }}-4"
                rel="module">

            </div>
        </div>
    </div>
</section>

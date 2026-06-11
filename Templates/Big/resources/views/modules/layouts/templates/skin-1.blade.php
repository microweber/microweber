<?php

/*

type: layout

name: CLEAN CONTAINER

position: 0

*/

?>

<?php
if (empty($classes['padding_top'])) {
    $classes['padding_top'] = 'p-t-100';
}
if (empty($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'p-b-100';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section {{ $layout_classes ?? '' }} clean-container edit" field="layout-skin-1-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container no-element container">
        <x-row>
            <div class="col-12 allow-select">
                <h1 class="element" data-mwplaceholder="Enter your title here"></h1>

                <p class="element" data-mwplaceholder="This is sample text for your page"></p>
            </div>
        </x-row>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>

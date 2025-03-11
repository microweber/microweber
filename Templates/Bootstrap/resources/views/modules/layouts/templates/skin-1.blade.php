<?php

/*

type: layout

name: CLEAN container mw-layout-container

position: 0

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = 'p-t-100';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = 'p-b-100';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section <?php print $layout_classes; ?> nodrop clean-container mw-layout-container edit" field="layout-skin-1-{{ $params['id'] }}" rel="module">
    <div class="container mw-layout-container">
        <div class="row">
            <div class="col-12 col-md-12 allow-drop">
                <div class="mw-row">
                    <div class="mw-col" style="width:100%">
                        <div class="mw-col-container mw-layout-container">
                            <div class="mw-empty-element"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

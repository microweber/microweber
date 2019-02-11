<?php

/*

type: layout

name: CLEAN CONTAINER

position: 0

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<div class="nodrop clean-container edit <?php print $layout_classes; ?>" field="layout-skin-1-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="allow-drop">
            <div class="mw-row">
                <div class="mw-col" style="width:100%">
                    <div class="mw-col-container">
                        <div class="mw-empty"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
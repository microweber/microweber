<?php

/*

type: layout

name: Sample text

position: 4

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

<div class="nodrop safe-mode edit <?php print $layout_classes; ?>" field="layout-skin-5-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="box-container allow-drop">
            <h2><?php print _lang('Page Title', 'templates/liteness'); ?></h2>

            <p><?php print _lang('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur non tincidunt turpis. Integer accumsan dolor dui, non pulvinar ante sodales ut. Phasellus et varius orci, nec ornare tellus.', 'templates/liteness'); ?></p>
        </div>
    </div>
</div>
<?php

/*

type: layout

name: CLEAN CONTAINER

position: 0

*/

?>

<?php
include 'settings_padding_front.php';
if($padding === null OR $padding === false){
    $padding = 'space--0';
}
?>

<?php
/* Overlay with Color */
$is_color = get_option('is_color', $params['id']);
if ($is_color === null OR $is_color === false OR $is_color == '') {
    $is_color = false;
}
?>
<?php if ($is_color != false): ?>
    <style>
        <?php print '#' . $params['id'] ?> .clean-container{
            background: <?php print $is_color; ?>;
        }
    </style>
<?php endif; ?>


<section class="nodrop clean-container edit <?php print $padding ?>" field="layout-skin-69-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="element mw-empty-element allow-drop"></div>
            </div>
        </div>
    </div>
</section>

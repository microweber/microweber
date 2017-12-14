<?php

/*

type: layout

name: Simple Page Title + Slogan

position: 2

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php
/* Overlay with Color */
$is_color = get_option('is_color', $params['id']);
if ($is_color === null OR $is_color === false OR $is_color == '') {
    $is_color = false;
}
?>
<?php if ($is_color != false): ?>
    <style>
        <?php print '#' . $params['id'] ?> {
            background: <?php print $is_color; ?>;
        }
    </style>
<?php endif; ?>

<?php
include 'settings_height_front.php';
if ($height === null OR $height === false OR $height == '') {
    $height = '40';
}
?>

<section class="height-<?php print $height; ?> edit safe-mode <?php print $padding ?> nodrop" field="layout-skin-2-<?php print $params['id'] ?>" rel="module">
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center  allow-drop">
                <h2>Clean Page</h2>
                <div><span class="safe-element"><em>Effective February 9th, 2016</em></span></div>
            </div>
        </div>
    </div>
</section>
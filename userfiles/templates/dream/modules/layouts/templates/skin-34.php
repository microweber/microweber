<?php

/*

type: layout

name: Title 3

position: 34

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

<section class="height-<?php print $height; ?> edit safe-mode nodrop <?php print $padding ?>" field="layout-skin-34-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h6><?php _lang("Case Study", "templates/dream"); ?></h6>
                <h3><?php _lang("Reimagining the Chat Experience", "templates/dream"); ?></h3>
                <p class="lead">
                    <?php _lang("Dream provided conceptualization through design, deployment <br> and marketing for this burgeoning startup", "templates/dream"); ?>
                </p>
            </div>
        </div>
    </div>
</section>
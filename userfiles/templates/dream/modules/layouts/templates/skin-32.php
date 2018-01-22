<?php

/*

type: layout

name: Title 1

position: 32

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

<section class="height-<?php print $height; ?> edit safe-mode nodrop <?php print $padding ?>" field="layout-skin-32-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center allow-drop">
                <h3><?php _lang("Your Creative Collective", "templates/dream"); ?></h3>
                <p class="lead">
                    <?php _lang("Our diverse team comprises talent from a range of design disciplines working together to deliver effective solutions", "templates/dream"); ?>
                </p>
            </div>
        </div>

    </div>
</section>
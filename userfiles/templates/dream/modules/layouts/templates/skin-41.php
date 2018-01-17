<?php

/*

type: layout

name: Title Image BG 2

position: 41

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Is Parallax */
$is_parallax = get_option('is_parallax', $params['id']);
if ($is_parallax === null OR $is_parallax === false OR $is_parallax == '') {
    $is_parallax = 'no';
}

/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '5';
}
?>

<?php
include 'settings_height_front.php';
if ($height === null OR $height === false OR $height == '') {
    $height = '70';
}
?>

<section class="height-<?php print $height; ?> safe-mode bg--dark imagebg page-title page-title--animate <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" field="layout-skin-41-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder background--bottom">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero21.jpg');"></div>
    </div>
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 text-center allow-drop">
                <h3><?php _lang("Development Process", "templates/dream"); ?></h3>
                <p class="lead"><?php _lang("Our rigorous process ensures your project is managed strategically and efficiently from initial conception through to final delivery and after-sales support", "templates/dream"); ?>.</p>
            </div>
        </div>

    </div>

</section>
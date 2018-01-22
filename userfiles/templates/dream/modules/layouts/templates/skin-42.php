<?php

/*

type: layout

name: Title Image BG 3

position: 42

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
    $overlay = '8';
}
?>

<?php
include 'settings_height_front.php';
if ($height === null OR $height === false OR $height == '') {
    $height = '60';
}
?>

<section class="imagebg safe-mode height-<?php print $height; ?> <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" field="layout-skin-42-<?php print $params['id'] ?>"
         rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero4.jpg');"></div>

    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h1><?php _lang("Image Showcase", "templates/dream"); ?></h1>
                <p class="lead"><?php _lang("Showcase a gallery of images with lightbox capability", "templates/dream"); ?></p>
            </div>
        </div>
    </div>
</section>
<?php

/*

type: layout

name: Cover with Text 3

position: 29

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '3';
}
?>

<section class="height-100 safe-mode imagebg <?php if($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" field="layout-skin-29-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>wedding-1.jpg');"></div>

    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h1 class=""><?php _lang("Sam Gibbs &amp; Aisha Roberts", "templates/dream"); ?></h1>
                <p><?php _lang("August 6th 2017, Flinders Ranges, Victoria", "templates/dream"); ?></p>
            </div>
        </div>
    </div>
</section>
<?php

/*

type: layout

name: Cover with Subscriber

position: 27

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '4';
}
?>

<section class="cover cover-12 safe-mode form--dark imagebg height-100 <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" field="layout-skin-27-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero14.jpg');"></div>

    <div class="container pos-vertical-center text-center-xs">
        <div class="row pos-vertical-align-columns">
            <div class="col-md-7 col-sm-8 col-sm-offset-2 allow-drop">
                <h2><?php _lang("Build smart, effective websites in no time with Dream", "templates/dream"); ?></h2>
            </div>
            <div class="col-md-5 col-sm-8 col-sm-offset-2 voh">
                <module type="newsletter" template="skin-1"/>
            </div>
        </div>

    </div>

</section>
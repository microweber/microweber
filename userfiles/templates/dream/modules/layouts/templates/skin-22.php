<?php

/*

type: layout

name: Cover Media 5

position: 22

*/

?>

<?php include 'settings_padding_front.php'; ?>
<?php include 'settings_is_parallax_front.php'; ?>
<?php include 'settings_is_color_front.php'; ?>
<?php
/* Overlay */
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false) {
    $overlay = '8';
}
?>

<section class="height-80 safe-mode nodrop imagebg <?php if($is_parallax == 'yes'): ?>parallax<?php endif; ?> bg--primary edit <?php print $padding ?>" field="layout-skin-22-<?php print $params['id'] ?>" rel="module" data-overlay="<?php print $overlay; ?>">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero26.jpg');"></div>
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h1><?php _lang("Combine, Edit, Deploy.", "templates/dream"); ?></h1>
                <p class="lead">
                    <?php _lang("Building a beautiful website is simple with Dream and Microweber CMS & Website Builder", "templates/dream"); ?>
                </p>
                <module type="btn" text="Take a Your" class="inline-element"/>
            </div>
        </div>

    </div>
</section>
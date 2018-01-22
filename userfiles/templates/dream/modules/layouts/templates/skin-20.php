<?php

/*

type: layout

name: Title + Button with Background

position: 20

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

<section class="imagebg safe-mode section--even <?php if($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop edit <?php print $padding ?>" data-overlay="<?php print $overlay; ?>" field="layout-skin-20-<?php print $params['id'] ?>" rel="module">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero8.jpg');"></div>

    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 text-center allow-drop">
                <h4><?php _lang("What can Dream do for your business?", "templates/dream"); ?></h4>
                <module type="btn" text="Arrange A Consultation"/>
            </div>
        </div>
    </div>
</section>
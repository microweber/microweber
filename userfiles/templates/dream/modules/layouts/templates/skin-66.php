<?php

/*

type: layout

name: Icon Features 7

position: 66

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

<section class="features features-8 imagebg height-100 <?php if ($is_parallax == 'yes'): ?>parallax<?php endif; ?> nodrop safe-mode edit <?php print $padding ?>" data-overlay="<?php print $overlay; ?>"
         field="layout-skin-66-<?php print $params['id'] ?>" rel="module">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero15.jpg');"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?php _lang("Bold, Simple", "templates/dream"); ?>,
                    <em><?php _lang("Robust", "templates/dream"); ?>.</em>
                </h2>
                <p class="lead">
                    <?php _e("Pillar features a bevvy of content blocks and layouts to create your website") ?>.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="boxed boxed--lg voh">
                    <div class="feature col-sm-6">
                        <i class="icon icon--lg icon-Windows-2 safe-element"></i>
                        <h5><?php _lang("Lovingly Crafted Components", "templates/dream"); ?></h5>
                        <p class="lead allow-drop">
                            <?php _lang("Tailored for modern startups, portfolios shops and more", "templates/dream"); ?>.
                        </p>
                    </div>
                    <div class="feature col-sm-6">
                        <i class="icon icon--lg icon-Medal safe-element"></i>
                        <h5><?php _lang("Highly Regarded Author", "templates/dream"); ?></h5>
                        <p class="lead allow-drop">
                            <?php _lang("Awarded by Envato for providing high quality products", "templates/dream"); ?>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
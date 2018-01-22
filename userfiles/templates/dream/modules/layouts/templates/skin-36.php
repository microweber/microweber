<?php

/*

type: layout

name: Icon Features 11

position: 36

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="features safe-mode features-5 nodrop edit <?php print $padding ?>" field="layout-skin-36-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h3><?php _lang("Bold, Simple, Robust.", "templates/dream"); ?></h3>
                <p class="lead">
                    <?php _lang("Dream features a bevvy of content blocks and layouts to create your website", "templates/dream"); ?>.
                </p>
            </div>

            <div class="col-md-5 col-md-offset-1 col-sm-6 voh">
                <div class="feature feature-2">
                    <div class="feature__title">
                        <i class="icon icon-Orientation-2 safe-element"></i>
                        <h6><?php _lang("Beautifully Responsive", "templates/dream"); ?></h6>
                    </div>
                    <p><?php _lang("Every one of Dream’s stylish blocks are fully responsive,meaning that your site shines - regardless of the device your users are viewing from", "templates/dream"); ?>.</p>
                </div>
            </div>

            <div class="col-md-5 col-sm-6 voh">
                <div class="feature feature-2">
                    <div class="feature__title">
                        <i class="icon icon-People-onCloud safe-element"></i>
                        <h6><?php _lang("Six Months Free Support", "templates/dream"); ?></h6>
                    </div>
                    <p>
                        <?php _lang("Each purchase of Dream comes with six months of our much-lauded customer support. We have our own dedicated support forum setup to help you", "templates/dream"); ?>.
                    </p>
                </div>
            </div>

            <div class="col-sm-12">
                <img alt="device" src="<?php print template_url('assets/img/'); ?>device1.png">
            </div>
        </div>
    </div>
</section>
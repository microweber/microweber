<?php

/*

type: layout

name: Icon Features 10

position: 35

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="features safe-mode features-9 nodrop edit <?php print $padding ?>" field="layout-skin-35-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <h3><?php _lang("Showcase Features &amp; Benefits", "templates/dream"); ?></h3>
                <p class="lead">
                    <?php _lang("Dream features a bevvy of content blocks and layouts to create your website", "templates/dream"); ?>.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-md-push-4 col-md-offset-0 col-sm-6 col-sm-offset-3 text-center">
                <img alt="device" src="<?php print template_url('assets/img/'); ?>device2.png">
            </div>
            <div class="col-md-3 col-md-pull-4 col-sm-6 col-xs-6 text-right text-left-xs voh">
                <div class="feature feature-1 cloneable">
                    <i class="icon icon-Fingerprint-2 safe-element"></i>
                    <h5><?php _lang("Over 2,000 Icons", "templates/dream"); ?></h5>
                    <p><?php _lang("Dream includes the Icons Mind kit with each purchase.", "templates/dream"); ?></p>
                </div>
                <div class="feature feature-1 cloneable">
                    <i class="icon icon-Approved-Window safe-element"></i>
                    <h5><?php _lang("Intuitive Markup", "templates/dream"); ?></h5>
                    <p><?php _lang("Focussed on readability and performance: Tinker on", "templates/dream"); ?>!</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6 col-md-push-2 voh">
                <div class="feature feature-1 cloneable">
                    <i class="icon icon-Support safe-element"></i>
                    <h5><?php _lang("Top Notch Support", "templates/dream"); ?></h5>
                    <p><?php _lang("Each purchase of Dream includes 6 months of support.", "templates/dream"); ?></p>
                </div>
                <div class="feature feature-1 cloneable">
                    <i class="icon icon-Box-Open safe-element"></i>
                    <h5><?php _lang("Builder Included", "templates/dream"); ?></h5>
                    <p><?php _lang("Assemble pages with the included Microweber CMS & Website Builder.", "templates/dream"); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
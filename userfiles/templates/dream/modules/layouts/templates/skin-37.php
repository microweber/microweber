<?php

/*

type: layout

name: Icon Features 2

position: 37

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="bg--secondary safe-mode imageblock features features-1 nodrop edit <?php print $padding ?>" field="layout-skin-37-<?php print $params['id'] ?>" rel="module">
    <div class="imageblock__content col-md-5 col-sm-3 pos-left">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero8.jpg');"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-push-6 col-sm-8 col-sm-push-4">
                <div class="allow-drop">
                    <h3><?php _lang("Diverse, Beautiful Components", "templates/dream"); ?></h3>
                    <p class="lead">
                        <?php _lang("Dream features a bevy of content blocks", "templates/dream"); ?>
                        <br class="visible-md visible-lg"> <?php _lang("and layouts to create your website", "templates/dream"); ?>.
                    </p>
                </div>
                <hr>
                <div class="col-xs-6 voh cloneable">
                    <div class="feature feature-1">
                        <i class="icon icon-Fingerprint safe-element"></i>
                        <h5><?php _lang("Over 2,000 Icons", "templates/dream"); ?></h5>
                        <p><?php _lang("Dream includes the Icons Mind kit with each purchase.", "templates/dream"); ?></p>
                    </div>
                </div>
                <div class="col-xs-6 voh cloneable">
                    <div class="feature feature-1">
                        <i class="icon icon-Support safe-element"></i>
                        <h5><?php _lang("Support Included", "templates/dream"); ?></h5>
                        <p><?php _lang("Each Dream purchase comes with 6 months support included.", "templates/dream"); ?></p>
                    </div>
                </div>
                <div class="col-xs-6 voh cloneable">
                    <div class="feature feature-1">
                        <i class="icon icon-Astronaut safe-element"></i>
                        <h5><?php _lang("Perfect for startups", "templates/dream"); ?></h5>
                        <p><?php _lang("Including tons of vibrant, carefully styled componenents.", "templates/dream"); ?></p>
                    </div>
                </div>
                <div class="col-xs-6 voh cloneable">
                    <div class="feature feature-1">
                        <i class="icon icon-Bag-Coins safe-element"></i>
                        <h5><?php _lang("E-commerce styling", "templates/dream"); ?></h5>
                        <p><?php _lang("Beautiful product pages and unique sidebar cart included.", "templates/dream"); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
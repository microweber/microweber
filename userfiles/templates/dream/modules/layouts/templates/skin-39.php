<?php

/*

type: layout

name: Icon Features 4

position: 39

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="features safe-mode features-6 bg--secondary nodrop edit <?php print $padding ?>" field="layout-skin-39-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-7">
                <img alt="pic" src="<?php print template_url('assets/img/'); ?>feature-1.png">
            </div>
            <div class="col-sm-5 voh allow-drop">
                <h3><?php _lang("Your Site", "templates/dream"); ?>,
                    <em><?php _lang("Your Way", "templates/dream"); ?>.</em>
                </h3>
                <p class="lead">
                    <?php _lang("A multitude of colour and font options make Dream's look dynamic and adaptable", "templates/dream"); ?>.
                </p>
                <div class="feature feature-2 col-xs-6 col-sm-12 cloneable">
                    <div class="feature__title nodrop">
                        <i class="icon icon-Fingerprint-2 safe-element"></i>
                        <h6><?php _lang("Over 2,000 Icons", "templates/dream"); ?></h6>
                    </div>
                    <p>
                        <?php _lang("Dream includes the premium Icons Mind icon kit: A stunning collection of icons to suit multiple purposes", "templates/dream"); ?>.
                    </p>
                </div>
                <div class="feature feature-2 col-xs-6 col-sm-12 cloneable">
                    <div class="feature__title nodrop">
                        <i class="icon icon-Photo-2 safe-element"></i>
                        <h6><?php _lang("Beautiful Typography", "templates/dream"); ?></h6>
                    </div>
                    <p>
                        <?php _lang("Dream includes attractive and flexible font pairs to suit a range of purposes - we've done the hunting for you you", "templates/dream"); ?>!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
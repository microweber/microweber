<?php

/*

type: layout

name: Cover Device

position: 21

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="cover safe-mode cover-9 nodrop edit <?php print $padding ?>" field="layout-skin-21-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center allow-drop">
                <img alt="<?php _lang("Logo", "templates/dream"); ?>" class="logo" src="<?php print template_url('assets/img/'); ?>logo-large-dark.png">
                <p class="lead">
                    <?php _lang("A stunning collection of <br> front-end web components", "templates/dream"); ?>.
                </p>

                <module type="btn" text="<?php _lang("Take a Your", "templates/dream"); ?>" template="bootstrap" class="inline-element"/>
                <module type="btn" text="<?php _lang("Get It On The App Store", "templates/dream"); ?>" template="bootstrap" class="inline-element"/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <img class="cover__image" alt="<?php _lang("Device", "templates/dream"); ?>" src="<?php print template_url('assets/img/'); ?>device1.png">
            </div>
        </div>

    </div>

</section>
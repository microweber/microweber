<?php

/*

type: layout

name: Cover Split

position: 23

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="height-100 safe-mode cover cover-2 nodrop edit <?php print $padding ?>" field="layout-skin-23-<?php print $params['id'] ?>" rel="module">
    <div class="col-md-6 col-sm-5">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero6.jpg');"></div>
    </div>
    <div class="col-md-6 col-sm-7 bg--white text-center">
        <div class="pos-vertical-center allow-drop">
            <img class="logo" alt="Dream" src="<?php print template_url('assets/img/'); ?>logo-large-dark.png">
            <p class="lead">
                <?php _lang("A beautiful collection of <br> hand-crafted web components", "templates/dream"); ?>
            </p>

            <module type="btn" template="default" text="<?php _lang("Purchase Dream", "templates/dream"); ?>"/>
        </div>

        <div class="col-sm-12 text-center pos-absolute pos-bottom">
            <module type="social_links"/>
        </div>
    </div>
</section>
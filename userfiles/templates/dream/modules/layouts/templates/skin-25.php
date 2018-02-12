<?php

/*

type: layout

name: Cover Media 3

position: 25

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="cover cover-10 safe-mode imagebg image--light nodrop edit <?php print $padding ?>" field="layout-skin-25-<?php print $params['id'] ?>" rel="module">
    <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>large11.jpg');"></div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <img alt="Logo" class="logo" src="<?php print template_url('assets/img/'); ?>logo-dark.png">
                <p class="lead">
                   <?php _lang(" A stunning collection of <br> front-end web components", "templates/dream"); ?>.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 text-center">
                <module type="video" />
            </div>
        </div>
    </div>
</section>
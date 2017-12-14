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
                <img alt="Logo" class="logo" src="<?php print template_url('assets/img/'); ?>logo-large-dark.png">
                <p class="lead">
                    A stunning collection of
                    <br> front-end web components.
                </p>

                <module type="btn" text="Take a Your" template="bootstrap" class="inline-element"/>
                <module type="btn" text="Get It On The App Store" template="bootstrap" class="inline-element"/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <img class="cover__image" alt="Device" src="<?php print template_url('assets/img/'); ?>device1.png">
            </div>
        </div>

    </div>

</section>
<?php

/*

type: layout

name: Left Text - Right Image

position: 12

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="imageblock safe-mode about-1 bg--secondary nodrop edit <?php print $padding ?>" field="layout-skin-12-<?php print $params['id'] ?>" rel="module">
    <div class="imageblock__content col-md-6 col-sm-4 pos-right">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero29.jpg');"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-8 allow-drop">
                <h3>Assemble pages with over a lot of content blocks</h3>
                <p>
                    Dream combines smart, modern styling with all the features you’ll need to launch websites of almost any kind. Achieve results faster than ever with the included Microweber CMS & Website Builder.
                </p>

                <module type="btn" template="bootstrap" text="Click Here" button_style="btn-simple" />
            </div>
        </div>
    </div>
</section>
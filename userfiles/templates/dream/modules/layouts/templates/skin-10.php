<?php

/*

type: layout

name: Left Image - Right Text

position: 10

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="imageblock safe-mode about-2 bg--dark nodrop edit <?php print $padding ?>" field="layout-skin-10-<?php print $params['id'] ?>" rel="module">
    <div class="imageblock__content col-md-6 col-sm-4 pos-left">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero28.jpg');"></div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-5 col-md-push-7 col-sm-8 col-sm-push-4 allow-drop">
                <h2>Build smart, effective websites in no time.</h2>
                <p>
                    Dream features a bevy of content blocks and layouts to create websites of almost any kind &mdash; easier than ever before.
                </p>
            </div>
        </div>
    </div>
</section>
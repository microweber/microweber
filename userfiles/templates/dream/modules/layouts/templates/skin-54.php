<?php

/*

type: layout

name: Long Feature 2 Right

position: 54

*/

?>

<?php include 'settings_padding_front.php'; ?>


<section class="imageblock safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-54-<?php print $params['id'] ?>" rel="module">
    <div class="imageblock__content col-md-6 col-sm-4 pos-right">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero25.jpg');"></div>
    </div>

    <div class="col-md-6 col-sm-8">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="feature feature-1 service-1 text-center allow-drop">
                <i class="icon icon--lg icon-Compass-4 safe-element"></i>
                <h4><?php _lang("Strategy & Innovation", "templates/dream"); ?></h4>
                <p><?php _lang("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500.", "templates/dream"); ?></p>
                <hr>
                <h6><?php _lang("Key Focus Areas Include", "templates/dream"); ?></h6>
                <ul class="bullets">
                    <li>
                        <em><?php _lang("Project Management", "templates/dream"); ?></em>
                    </li>
                    <li>
                        <em><?php _lang("Product Design", "templates/dream"); ?></em>
                    </li>
                    <li>
                        <em><?php _lang("Market Feasability", "templates/dream"); ?></em>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
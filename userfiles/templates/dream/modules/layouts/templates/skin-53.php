<?php

/*

type: layout

name: Long Feature 2 Left

position: 53

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="imageblock safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-53-<?php print $params['id'] ?>" rel="module">
    <div class="imageblock__content col-md-6 col-sm-4 pos-left">
        <div class="background-image-holder" style="background-image: url('<?php print template_url('assets/img/'); ?>hero21.jpg');"></div>
    </div>

    <div class="col-md-6 col-md-push-6 col-sm-8 col-sm-push-4">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="feature feature-1 service-1 text-center allow-drop">
                <i class="icon icon--lg icon-Sidebar-Window safe-element"></i>
                <h4><?php _lang("Interface Design", "templates/dream"); ?></h4>
                <p><?php _lang("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500.", "templates/dream"); ?></p>
                <hr>
                <h6><?php _lang("Key Focus Areas Include", "templates/dream"); ?></h6>
                <ul class="bullets">
                    <li>
                        <em><?php _lang("Motion Interaction", "templates/dream"); ?></em>
                    </li>
                    <li>
                        <em><?php _lang("Rapid Prototyping", "templates/dream") ?></em>
                    </li>
                    <li>
                        <em><?php _lang("Continual Improvement", "templates/dream"); ?></em>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
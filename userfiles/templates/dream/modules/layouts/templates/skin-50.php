<?php

/*

type: layout

name: Contact Methods

position: 50

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="features safe-mode features-10 nodrop edit <?php print $padding ?>" field="layout-skin-50-<?php print $params['id'] ?>" rel="module">
    <div class="feature bg--white col-md-4 text-center allow-drop cloneable">
        <i class="icon icon--lg icon-Map-Marker2 safe-element"></i>
        <h4><?php _lang("Drop on in", "templates/dream"); ?></h4>
        <p>
            <?php _lang("Suite 203, Level 4", "templates/dream"); ?>
            <br/> <?php _lang("124 Koornang Road", "templates/dream"); ?>
            <br/> <?php _lang("Carnegie, Victoria 3183", "templates/dream"); ?>
        </p>
    </div>
    <div class="feature bg--secondary col-md-4 text-center allow-drop cloneable">
        <i class="icon icon--lg icon-Phone-2 safe-element"></i>
        <h4><?php _lang("Give us a call", "templates/dream"); ?></h4>
        <p>
            <?php _lang("Office", "templates/dream"); ?>: (03) 9283 2617
            <br/> <?php _lang("Fax", "templates/dream"); ?>: +61 3827 3590
        </p>
    </div>
    <div class="feature bg--dark col-md-4 text-center allow-drop cloneable">
        <i class="icon icon--lg icon-Computer safe-element"></i>
        <h4><?php _lang("Connect online", "templates/dream"); ?></h4>
        <p>
            <?php _lang("Email", "templates/dream"); ?>:
            <a href="#">hello@microweber.com</a>
            <br/> <?php _lang("Twitter", "templates/dream"); ?>:
            <a href="#">@microweber</a>
        </p>
    </div>
</section>
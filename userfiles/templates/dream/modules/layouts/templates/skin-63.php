<?php

/*

type: layout

name: Title

position: 63

*/

$padding = get_option('padding', $params['id']);

if ($padding == false) {
    $padding = 'space--0';
}
?>

<section class="edit safe-mode nodrop <?php print $padding ?>" field="layout-skin-63-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 text-center allow-drop">
                <h3><?php _lang("Your Creative Collective", "templates/dream"); ?></h3>
            </div>
        </div>
    </div>
</section>
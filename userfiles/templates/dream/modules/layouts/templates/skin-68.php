<?php

/*

type: layout

name: Page Title

position: 68

*/

?>

<?php
include 'settings_height_front.php';
if ($height === null OR $height === false OR $height == '') {
    $height = '20';
}
?>

<section class="height-<?php print $height; ?> page-title page-title--animate nodrop safe-mode edit" field="layout-skin-68-<?php print $params['id'] ?>" rel="module">
    <div class="container pos-vertical-center">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h1><?php _lang("Title", "templates/dream"); ?></h1>
                <p class="lead"><?php _lang("Showcase blog posts in a classic column arrangement", "templates/dream"); ?></p>
            </div>
        </div>
    </div>
</section>
<?php

/*

type: layout

name: Titles 7

position: 7

categories: Titles

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}

if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>



<section class="edit safe-mode nodrop" field="layout-titles-skin-7-<?php print $params['id'] ?>" rel="module" data-parallax="true" data-overlay-x="1">
    <div class="background-image-holder mh-450 d-flex align-items-end" style="background-image: url('<?php print template_url(); ?>assets/img/layouts/title-7.jpg')">
        <div class="container">
            <div class="row text-center nodrop" style="padding-bottom: 150px; padding-top: 150px;">
                <div class="col-8 col-md-6 mx-auto allow-drop" style="background-color: #FFFFFF; padding: 80px 50px">
                    <h3>The future is here and belongs to you.</h3>
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
                </div>
            </div>
        </div>
   </div>
</section>

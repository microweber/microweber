<?php

/*

type: layout

name: Text block 14

position: 14

categories: Text block

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = 'pt-5';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = 'pb-5';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<section class="section py-0 edit safe-mode nodrop" field="layout-text-block-skin-14-<?php print $params['id'] ?>" rel="module" data-background-position="center center" data-overlay-black="true" data-overlay="5">
    <div class="background-image-holder mh-100vh d-flex align-items-center" style="background-image: url('<?php print template_url(); ?>assets/img/layouts/text-blocks-1.jpg')" >
        <div class="container <?php print $layout_classes; ?>">
            <div class="row text-center">
                <div class="col-12 col-lg-10 mx-auto  allow-drop">
                    <p class="lead-2 text-white">Lorem Ipsum is simply dummy text of the printing. </p>
                    <br>
                    <p class="lead text-white">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>                </div>
            </div>
        </div>
    </div>
</section>

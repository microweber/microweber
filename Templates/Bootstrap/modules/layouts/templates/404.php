<?php

/*

type: layout

name: 404

position: 10

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = 'p-t-50';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = 'p-b-50';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section <?php print $layout_classes; ?> edit safe-mode" field="layout-404-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="not_found_text col-4 align-self-center">
                <h1><?php _lang("Oops", "templates/new-world"); ?>!</h1>
                <p class="my-3"><?php _lang("A 404 error is a standard HTTP error
                 message code that means the website you
                  were trying to reach couldn't be found on the server", "templates/new-world"); ?>.
                </p>
                <module type="btn" button_size="px-6" button_text="Go back"/>
            </div>

            <div class="col-8 text-center not_found_img">
                <img src="<?php print template_url(); ?>assets/img/sections/404_graphic.png"/>
            </div>
        </div>
    </div>
</section>

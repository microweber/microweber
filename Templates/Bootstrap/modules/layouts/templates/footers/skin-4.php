<?php

/*

type: layout

name: Footers 4

position: 4

categories: Footers

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

<section class=" footer-background <?php print $layout_classes; ?> safe-mode">
    <div class="container-fluid">
        <div class="row gap-y align-items-center">
            <div class="col-md-3 text-center text-md-start">
                <small class="edit" field="footer-reserved-skin-4-<?php print $params['id'] ?>" rel="module">© All Rights Reserved. Your Website Design</small>
            </div>

            <div class="col-md-6">
                <module type="menu" template="simple" data-class="nav nav-center justify-content-center" id="footer_menu" name="footer_menu" field="footer-menu-skin-23-<?php print $params['id'] ?>" rel="module"/>
            </div>

            <div class="col-md-3 text-center text-md-end">

                <p class="mb-0 noedit" style="color: #FFFFFF"><?php print powered_by_link(); ?></p>
            </div>
        </div>
    </div>
    <?php include('footer_cart.php'); ?>
</section>


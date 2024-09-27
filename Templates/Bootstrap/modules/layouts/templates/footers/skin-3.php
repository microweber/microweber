<?php

/*

type: layout

name: Footers 3

position: 3

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

<section class="  footer-background <?php print $layout_classes; ?> edit safe-mode  "
         field="layout-footer-skin-3-<?php print $params['id'] ?>" rel="module">
<!-- Footer -->
    <div class="container d-md-flex">
       <div class="col-sm-6 col d-flex">
           <module type="logo" id="footer-logo-<?php print $params['id']; ?>" />

       </div>

        <div class="col-sm-6 col-12 d-md-flex justify-content-md-end align-self-center mt-sm-0 mt-3">
            <module type="menu" class="footer-skin-link" template="simple" name="footer_menu_6"/>
        </div>
    </div>
    <?php include('footer_cart.php'); ?>
</section>

<section class="py-2" style="background-color: #f5f5f5;">
    <div class="container py-2" >
        <div class="col-12 d-md-flex text-center">
            <small class="col-sm-6 text-md-start text-center edit" field="footer-reserved-skin-15-<?php print $params['id'] ?>" rel="module">© All Rights Reserved. Your Website Design</small>
            <small class="col-sm-6 mb-0 noedit text-md-end text-center"><?php print powered_by_link(); ?></small>
        </div>
    </div>
</section>

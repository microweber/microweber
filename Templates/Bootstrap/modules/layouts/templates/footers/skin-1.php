<?php

/*

type: layout

name: Footers 1

position: 1

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
         field="layout-footer-skin-1-<?php print $params['id'] ?>" rel="module">
    <!-- Footer -->
    <div class="container">
       <div class="row d-flex justify-content-center text-center">
           <div class="row col-md-12 col d-flex justify-content-center mb-md-7">
               <div class="col-md-3 col">
                   <p class="font-weight-bold ms-3"><?php _lang('Category', 'templates/big'); ?> </p>
                   <module type="menu" template="simple" name="footer_menu_1"/>
               </div>

               <div class="col-md-3 col">
                   <p class="font-weight-bold ms-3"><?php _lang('Category', 'templates/big'); ?></p>
                   <module type="menu" template="simple" name="footer_menu_2"/>
               </div>

               <div class="col-md-3 col">
                   <p class="font-weight-bold ms-3"><?php _lang('Category', 'templates/big'); ?></p>
                   <module type="menu" template="simple" name="footer_menu_3"/>
               </div>

               <div class="col-md-3 col">
                   <div class="col-12 text-center">
                       <p class="lead font-weight-bold"><?php _lang('Follow', 'templates/big'); ?></p>
                       <module type="social_links"/>
                   </div>
               </div>
           </div>
       </div>
    </div>
    <?php include('footer_cart.php'); ?>
</section>

<section class="py-2" style="background-color: #f5f5f5;">
    <div class="container py-2" >
        <div class="col-12 d-md-flex text-center">
            <small class="col-sm-6 text-md-start text-center edit" field="footer-reserved-skin-4-<?php print $params['id'] ?>" rel="module">© All Rights Reserved. Your Website Design</small>
            <small class="col-sm-6 mb-0 noedit text-md-end text-center"><?php print powered_by_link(); ?></small>
        </div>
    </div>
</section>

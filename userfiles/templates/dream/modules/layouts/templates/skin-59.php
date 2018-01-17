<?php

/*

type: layout

name: Shop products

position: 59

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="bg--white safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-59-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="related-products">
                <div class="col-sm-12">
                    <h4><?php _lang("New Products", "templates/dream"); ?></h4>
                </div>

                <module type="shop/products" template="skin-4" limit="3" />
            </div>
        </div>
    </div>
</section>
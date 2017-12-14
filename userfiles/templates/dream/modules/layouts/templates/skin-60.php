<?php

/*

type: layout

name: Shop products 2

position: 60

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="masonry-contained safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-60-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="masonry-shop">
                <div class="col-sm-12">
                    <div class="elements--title">
                        <h4>New Products</h4>
                    </div>
                </div>
                <module type="shop/products" limit="3" hide-paging="true"/>
            </div>
        </div>
    </div>
</section>
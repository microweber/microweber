<?php

/*

type: layout

name: Shop products 3

position: 62

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="masonry-contained safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-62-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row">
            <div class="masonry-shop">
                <module type="shop/products" template="skin-1" limit="3" hide-paging="true"/>
            </div>
        </div>
    </div>
</section>
<?php

/*

type: layout

name: Shop products 3

position: 61

*/

?>

<?php include 'settings_padding_front.php'; ?>

<section class="wide-grid masonry-shop safe-mode nodrop edit <?php print $padding ?>" field="layout-skin-61-<?php print $params['id'] ?>" rel="module">
    <module type="shop/products" template="skin-3" limit="4" hide-paging="true"/>
</section>
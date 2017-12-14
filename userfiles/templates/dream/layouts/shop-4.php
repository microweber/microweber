<?php

/*

type: layout
content_type: dynamic
name: Shop 4
is_shop: y
description: Showcase products in this stylish tiled masonry layout.
position: 4
*/


?>
<?php include template_dir() . "header.php"; ?>

<div class="edit" rel="content" field="dream_content">
    <module type="layouts" template="skin-42"/>


    <section class="wide-grid masonry masonry-shop">
        <module type="categories" content_id="<?php print PAGE_ID; ?>" />

        <module type="shop/products" limit="18" description-length="70" template="skin-3"/>
        <div class="clearfix"></div>
    </section>
</div>

<?php include template_dir() . "footer.php"; ?>

<?php

/*

type: layout
content_type: dynamic
name: Shop 2
is_shop: y
description: Showcase shop items in a full-width arrangement
position: 4
*/


?>
<?php include template_dir() . "header.php"; ?>

<div class="edit" rel="content" field="dream_content">
    <module type="layouts" template="skin-68"/>

    <section class="masonry-contained">
        <div class="container">
            <div class="row">
                <div class="masonry-shop">
                    <module type="categories" content-id="<?php print PAGE_ID; ?>" />

                    <module type="shop/products" limit="18" description-length="70" template="skin-1"/>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include template_dir() . "footer.php"; ?>

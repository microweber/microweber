<?php

/*

type: layout
content_type: dynamic
name: Shop 3
is_shop: y
description: Showcase shop items in this classic sidebar arrangement
position: 4
*/


?>
<?php include template_dir() . "header.php"; ?>

<div class="edit" rel="content" field="dream_content">
    <module type="layouts" template="skin-68"/>
</div>

<section class="masonry-contained">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="masonry-shop">
                    <module type="shop/products" limit="18" description-length="70" template="skin-2"/>
                </div>
            </div>

            <div class="col-md-3 hidden-sm hidden-xs">
                <?php include "shop_sidebar.php"; ?>
            </div>
        </div>
    </div>
</section>


<?php include template_dir() . "footer.php"; ?>

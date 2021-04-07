<?php

/*

type: layout
name: Default
description: Full width shop template with filters

*/

?>

<div class="row justify-content-between">

    <div class="col-sm-12 col-md-8 col-lg-9 col-xl-9">
        <module type="shop/products_v2" content-id="<?php print PAGE_ID; ?>" />
    </div>

    <div class="col-sm-12 col-md-4 col-lg-3 col-xl-2">
        <div class="edit allow-drop" field="default_shop_sidebar" rel="inherit">
            <div class="sidebar">

                <div class="sidebar__widget m-b-40">
                    <h4 class="m-b-20"><?php _lang("Filters" ); ?></h4>
                    <module type="shop/products/filter" content-id="<?php print PAGE_ID; ?>" />
                </div>

                <div class="sidebar__widget m-b-40">
                    <h4 class="m-b-20"><?php _lang("Categories"); ?></h4>

                    <module type="categories" content-id="<?php print PAGE_ID; ?>" />
                </div>

                <div class="sidebar__widget m-b-40">
                    <h4 class="m-b-20"><?php _lang("Tags" ); ?></h4>
                    <module type="tags" content-id="<?php print PAGE_ID; ?>" />
                </div>

            </div>
        </div>
    </div>
</div>

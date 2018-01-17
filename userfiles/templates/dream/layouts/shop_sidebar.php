<div class="edit allow-drop" field="dream_shop_sidebar" rel="inherit">
    <div class="sidebar">
        <div class="sidebar__widget">
            <h6><?php _lang("Search Site", "templates/dream"); ?></h6>
            <hr>
            <module type="search" data-search-type="shop"/>
        </div>

        <div class="sidebar__widget">
            <h6><?php _lang("Categories", "templates/dream"); ?></h6>
            <hr>
            <module type="categories" template="skin-1" content-id="<?php print PAGE_ID; ?>"/>
        </div>

        <div class="sidebar__widget">
            <h6><?php _lang("About Us", "templates/dream"); ?></h6>
            <hr>
            <p>
                <?php _lang("We're a digital focussed collective working with individuals and businesses to establish rich, engaging online presences.", "templates/dream"); ?>
            </p>
        </div>
    </div>
</div>



<?php only_admin_access() ?>

<div id="offers-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
    <div class="mw-ui-box-header" onclick="mw.accordion('#offers-accordion');">
        <div class="header-holder">
            <i class="mw-icon-pricetag"></i><?php _e("Promotions"); ?>
        </div>
    </div>

    <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
        <module type="shop/offers" view="admin"/>
    </div>
</div>
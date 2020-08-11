<?php only_admin_access() ?>

<div id="coupons-accordion" class="mw-ui-box mw-ui-box-silver-blue m-t-20">
    <div class="mw-ui-box-header" onclick="mw.accordion('#coupons-accordion');">
        <div class="header-holder">
            <i class="material-icons"> money_off </i> <?php _e("Coupons"); ?>
        </div>
    </div>

    <div class="mw-accordion-content mw-ui-box-content" style="display: none;">
        <module type="shop/coupons" view="admin"/>
    </div>
</div>
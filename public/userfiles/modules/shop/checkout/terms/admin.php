<?php
if (!user_can_access('module.shop.settings')) {
    return;
}

?>


<module type="users/terms/admin" terms-group="checkout" hide-label="true"/>



<?php must_have_access(); ?>




<module type="settings/group/website_group" />



<?php   if (get_option('shop_disabled', 'website') != 'y') { ?>
<module type="shop/settings" />
<?php } ?>
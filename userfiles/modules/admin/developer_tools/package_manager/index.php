<?php
if (!user_can('module.marketplace.index')) {
    return;
}
?>


<module type="admin/developer_tools/package_manager/browse_packages" />

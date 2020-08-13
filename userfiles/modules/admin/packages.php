
<?php
if (!user_can('module.packages')) {
    $module_info['name'] = 'Marketplace';
    include 'permission_denied_card.php';
    return;
}
?>

<?php if((isset($_GET['only_updates']) and $_GET['only_updates']) or isset($params['show_only_updates'])){ ?>
    <module type="admin/developer_tools/package_manager/browse_packages" show_only_updates="1" />
<?php } else { ?>
    <module type="admin/developer_tools/package_manager/browse_packages" />
<?php } ?>
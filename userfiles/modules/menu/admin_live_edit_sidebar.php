<?php //include_once($config['path_to_module'].'functions.php'); ?>
<?php
$rand = crc32(serialize($params));

$menu_name = get_option('menu_name', $params['id']);

if ($menu_name == false and isset($params['menu_name'])) {
    $menu_name = $params['menu_name'];
} elseif ($menu_name == false and isset($params['menu-name'])) {
    $menu_name = $params['menu-name'];
} elseif ($menu_name == false and isset($params['name'])) {
    $menu_name = $params['name'];
}


?>

<script>
    function openMenuManagerModal(module_id) {
        var modalOptions = {};
         var additional_params ={};
        additional_params.menu_name = '<?php print $menu_name  ?>'
        window.parent.mw.tools.open_global_module_settings_modal('menu/admin', module_id, modalOptions,additional_params)


    }
</script>



<div class="  mw-modules-tabs">
    <div class="mw-accordion-item-block mw-live-edit-module-manage-and-list-top">
        <a href="javascript:openMenuManagerModal('<?php print $params['id'] ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded">
            <span class="fas fa-list"></span> &nbsp;<?php print _e('Manage menu'); ?>
        </a>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-menu-settings">
                <?php include($config['path_to_module'] . 'admin_live_edit_sidebar_tab1.php'); ?>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
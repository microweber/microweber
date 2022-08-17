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
        window.mw.parent().tools.open_global_module_settings_modal('menu/admin', module_id, modalOptions,additional_params)


    }
</script>


<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
    <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
</nav>

<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="settings">
    </div>

    <div class="tab-pane fade" id="templates">
        <module type="admin/modules/templates"/>
    </div>
</div>



<div class="  mw-modules-tabs">
    <div class="mw-accordion-item-block mw-live-edit-module-manage-and-list-top">
        <a href="javascript:openMenuManagerModal('<?php print $params['id'] ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded">
            <span class="fa fa-list"></span> &nbsp;<?php _e('Manage menu'); ?>
        </a>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php _e('Settings'); ?>
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
                <i class="mw-icon-beaker"></i> <?php _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>

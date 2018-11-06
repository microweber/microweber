<?php only_admin_access() ?>
<?php
if (isset($params['for-module-id'])) {
    $params['id'] = $params['for-module-id'];

}

$is_shop = false;

if (isset($params['is_shop'])) {
    $is_shop = $params['is_shop'];
}

$dir_name = normalize_path(modules_path());
$posts_mod = $dir_name . 'content' . DS . 'admin_live_edit_tab1.php';
?>

<script>mw.lib.require('font_awesome5');</script>

<div class="mw-live-edit-module-manage-and-list-top">
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded"><span class="fas fa-list"></span> &nbsp; Manage posts</a>
    <a href="javascript:window.parent.mw.quick.edit('0','post', '', '0', '');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded"><span class="fas fa-plus-circle"></span> &nbsp; Add new post</a>
</div>

<div class="mw-accordion mw-accordion-window-height">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> Settings
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-posts-settings">
                <?php include($posts_mod); ?>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> Templates
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates" parent-module-id="<?php print $params['id'] ?>" for-module="<?php print $params['type'] ?>"/>
        </div>
    </div>
</div>


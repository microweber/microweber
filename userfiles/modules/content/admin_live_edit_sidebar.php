<?php must_have_access() ?>
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

$posts_parent_category = get_option('data-category-id', $params['id']);
$posts_parent_page = get_option('data-page-id', $params['id']);


$page_id_for_add = page_id();
$category_id_for_add = category_id();
$category_id_for_add = 0;

if ($posts_parent_category) {
    $category_id_for_add = $posts_parent_category;
}

if ($posts_parent_page) {
    $page_id_for_add = $posts_parent_page;
}

$mange_btn_text = _e('Manage content', true);

$cont_type_to_add = 'post';
if ($is_shop) {
    $cont_type_to_add = 'product';
    $mange_btn_text = _e('Manage products', true);

}


$add_new_text = _e('Add new ' . $cont_type_to_add, true);
?>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item-block mw-live-edit-module-manage-and-list-top">
        <a href="javascript:window.mw.parent().live_edit.showSettings('#<?php print $params['id'] ?>',{mode:'modal', liveedit:true});" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded">
            <span class="fas fa-list"></span> &nbsp;<?php print $mange_btn_text ?>
        </a>

        <a href="javascript:window.mw.parent().liveedit.manageContent.edit('0','<?php print $cont_type_to_add ?>', '', '<?php print $page_id_for_add ?>', '<?php print $category_id_for_add ?>');" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded">
            <span class="fas fa-plus-circle"></span> &nbsp;<?php print $add_new_text ?>
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
            <div class="module-live-edit-settings module-posts-settings">
                <?php include($posts_mod); ?>
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
            <module type="admin/modules/templates" parent-module-id="<?php print $params['id'] ?>" for-module="<?php print $params['type'] ?>"/>
        </div>
    </div>
</div>


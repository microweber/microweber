<?php if (!isset($data)) {
    $data = $params;
}

$custom_tabs = mw()->modules->ui('content.edit.tabs');
?>

<script>
    mw.lib.require('colorpicker');
</script>

<div id="settings-tabs">

    <div class="card style-1 mb-3 images">
        <div class="card-body pt-3">
            <module id="edit-post-gallery-main" type="pictures/admin" for="content" for-id="<?php print $data['id']; ?>" />
        </div>
    </div>

    <?php if ($data['content_type'] == 'page'): ?>
        <div class="mw-ui-box-content mw-settings-tabs-content menus">
            <?php event_trigger('mw_edit_page_admin_menus', $data); ?>
            <?php if (isset($data['add_to_menu'])): ?>
                <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>" add_to_menu="<?php print $data['add_to_menu']; ?>"/>
            <?php else: ?>
                <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>"/>
            <?php endif; ?>

            <?php event_trigger('mw_admin_edit_page_after_menus', $data); ?>
            <?php event_trigger('mw_admin_edit_page_tab_2', $data); ?>
        </div>
    <?php endif; ?>


    <div class="card style-1 mb-3 fields">
        <div class="card-body pt-3">
            <module type="custom_fields/admin" <?php if (trim($data['content_type']) == 'product'): ?> default-fields="price" <?php endif; ?> content-id="<?php print $data['id'] ?>" suggest-from-related="true" list-preview="true" id="fields_for_post_<?php print $data['id']; ?>"/>
            <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
        </div>
    </div>

    <?php if (trim($data['content_type']) == 'product'): ?>
        <div class="card style-1 mb-3">
            <div class="card-body pt-3">
                <?php event_trigger('mw_edit_product_admin', $data); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card style-1 mb-3 advanced">
        <div class="card-body pt-3">

            <?php event_trigger('mw_admin_edit_page_tab_4', $data); ?>
            <module type="content/views/advanced_settings" content-id="<?php print $data['id']; ?>" content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"/>
        </div>
    </div>

    <?php if ($data['content_type'] == 'old_page'): ?>
        <?php
        $no_content_type_setup_from_layout = false;
        if ($data['content_type'] != 'page' and $data['content_type'] != 'post' and $data['content_type'] != 'product') {
            $no_content_type_setup_from_layout = true;
        } else if (isset($data['subtype']) and $data['subtype'] != 'static' and $data['subtype'] != 'dynamic' and $data['subtype'] != 'post' and $data['subtype'] != 'product') {
            $no_content_type_setup_from_layout = true;
        }
        if ($no_content_type_setup_from_layout != false) {
            $no_content_type_setup_from_layout = ' no_content_type_setup="true" ';
        }
        ?>

        <div class="card style-1 mb-3 old-page">
            <div class="card-body pt-3">
                <div type="content/views/layout_selector" id="mw-quick-add-choose-layout" template-selector-position="bottom" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" <?php print $no_content_type_setup_from_layout ?> ></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($custom_tabs)): ?>
        <?php foreach ($custom_tabs as $item): ?>
            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
            <div class="card style-1 mb-3 custom-tabs">
                <div class="card-body pt-3"><?php print $html; ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php event_trigger('content/views/advanced_settings', $data); ?>
</div>


<script>
    $(document).ready(function () {
        pick1 = mw.colorPicker({
            element: '.mw-ui-color-picker',
            position: 'bottom-left',
            onchange: function (color) {

            }
        });
    });
</script>

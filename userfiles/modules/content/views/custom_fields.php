<div id="custom-fields-settings">
    <module type="custom_fields/admin" <?php if (trim($data['content_type']) == 'product'): ?> default-fields="price" <?php endif; ?> content-id="<?php print $data['id'] ?>" suggest-from-related="true" list-preview="true" id="fields_for_post_<?php print $data['id']; ?>"/>
    <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
</div>

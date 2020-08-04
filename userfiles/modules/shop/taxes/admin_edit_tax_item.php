<script>

    function mw_admin_edit_tax_item_submit_form(form) {
        var data = $(form).serialize()
        var url = mw.settings.api_url + 'shop/save_tax_item';
        $.post(url, data)
            .done(function (data) {
                mw.trigger("mw.admin.shop.tax.edit.item.saved");
            });

        return false;
    }

</script>
<?php
$default_item = array('id' => 0, 'name' => '', 'type' => '', 'rate' => '');
$item = array();
if (isset($params['tax_item_id'])) {
    $get = mw()->tax_manager->get('single=true&id=' . $params['tax_item_id']);
    if ($get) {
        $item = $get;
    }
}

$values = array_merge($default_item, $item);

?>

<form id="mw_edit_tax_item" onsubmit="return mw_admin_edit_tax_item_submit_form(this)" class="form-new-style">
    <input type="hidden" name="id" value="<?php print $values['id']; ?>"/>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Tax name'); ?></label>
        <input name="name" type="text" class="mw-ui-field element-block" required="required" value="<?php print $values['name']; ?>">
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Tax Type'); ?></label>
        <select name="type" class="mw-ui-field mw-ui-field-medium element-block">
            <option value="fixed" <?php if ($values['type'] == 'fixed') : ?> selected="selected" <?php endif; ?>><?php _e('Fixed'); ?></option>
            <option value="percent" <?php if ($values['type'] == 'percent') : ?> selected="selected" <?php endif; ?>><?php _e('Percent'); ?></option>
        </select>
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e('Tax rate'); ?></label>
        <input name="rate" type="text" class="mw-ui-field element-block" required="required" value="<?php print $values['rate']; ?>">
    </div>
    <br/>
    <input class="mw-ui-btn mw-ui-btn-info pull-right" type="submit" name="submit" value="<?php _e('Save'); ?>"/>
</form>

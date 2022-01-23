<script>
    function mw_admin_edit_tax_item_submit_form(form) {
        var data = $(form).serialize()
        var url = mw.settings.api_url + 'shop/save_tax_item';
        $.post(url, data)
            .done(function (data) {
                mw.trigger("mw.admin.shop.tax.edit.item.saved");
                mw.reload_module_everywhere('shop/taxes')
                mw.reload_module_everywhere('shop/taxes/admin_list_taxes')
                mw.reload_module_everywhere('shop/cart')

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

<form id="mw_edit_tax_item" onsubmit="return mw_admin_edit_tax_item_submit_form(this)">
    <input type="hidden" name="id" value="<?php print $values['id']; ?>"/>

    <div class="form-group">
        <label class="control-label"><?php _e('Tax name'); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('For example: VAT'); ?></small>
        <input name="name" type="text" class="form-control" required="required" value="<?php print $values['name']; ?>">
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e('Tax Type'); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('The tax can be fixed price or percentage of the price'); ?></small>
        <select name="type" class="selectpicker" data-width="100%">
            <option value="fixed" <?php if ($values['type'] == 'fixed') : ?> selected="selected" <?php endif; ?>><?php _e('Fixed'); ?></option>
            <option value="percent" <?php if ($values['type'] == 'percent') : ?> selected="selected" <?php endif; ?>><?php _e('Percent'); ?></option>
        </select>
    </div>

    <div class="form-group">
        <label class="control-label"><?php _e('Tax rate'); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('The amount of your tax. For example: 55.99'); ?></small>
        <input name="rate" type="text" class="form-control" required="required" value="<?php print $values['rate']; ?>">
    </div>

    <div class="text-end text-right">
        <button type="submit" class="btn btn-success btn-sm" name="submit"><?php _e('Save'); ?></button>
    </div>
</form>

<?php if (is_admin() == false) {


    return false;


}

$rand = uniqid();
?>
<?php if (!isset($params['for-module']) or !isset($params['for-module-id'])): ?>
    <?php print notif('Error: in module "' . $config['module'] . '" - You must set "for-module" and "for-module-id" parameters on this module', 'error');
    return; ?>
<?php endif; ?>
<script type="text/javascript">
    mw.require("forms.js", true);
</script>
<?php //$rand = uniqid(); ?>
<script type="text/javascript">


    isCreating = false;


    function mw_create_new_list_ <?php print $rand; ?>() {
        if (!isCreating) {

            isCreating = true;
            mw.form.post('.mw_create_new_forms_list<?php print $rand; ?>', '<?php print api_link('save_form_list'); ?>', function () {


                    mw.reload_module('<?php print $config['module'] ?>', function () {
                        isCreating = false;
                    });
                }
            );
        }
        return false;
    }


</script>
<?php $selected_list = get_option('list_id', $params['for-module-id']);
$data = get_form_lists('order_by=created_at desc&module_name=' . $params['for-module']);;
?>
<?php if (is_array($data)): ?>

    <div id="form_dropdown_lists">
        <div class="mw-ui-row-nodrop valign">
            <div class="mw-ui-col" style="width: 205px;">
                <label><?php _e("Save form entires to existing list"); ?></label></div>
            <div class="mw-ui-col">
                <select name="list_id" class="mw-ui-field mw_option_field"
                        option-group="<?php print $params['for-module-id'] ?>" style="width:100px;">

                    <option value="" <?php if (intval($selected_list) == 0): ?>   selected="selected"  <?php endif; ?>><?php _e("Default list"); ?></option>

                    <?php foreach ($data as $item): ?>
                        <option value="<?php print $item['id'] ?>" <?php if ((intval($selected_list) == intval($item['id']))): ?>   selected="selected"  <?php endif; ?>><?php print $item['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mw-ui-col text-center" style="width: 30px;text-align: center">
                <strong><?php _e("or"); ?></strong></div>
            <div class="mw-ui-col">

                <button class="mw-ui-btn pull-right"
                        onclick="mw.$('.mw_create_new_forms_list<?php print $rand; ?>, #form_dropdown_lists').toggle()"><?php _e("Create New"); ?></button>
            </div>
        </div>


    </div>
    <div class="mw_create_new_forms_list<?php print $rand; ?>" style="display: none;">

        <label class="mw-ui-label"><?php _e("Name of the new list"); ?></label>
        <input type="hidden" name="for_module" value="<?php print $params['for-module'] ?>"/>
        <input type="hidden" name="for_module_id" value="<?php print $params['for-module-id'] ?>"/>
        <input type="text" name="mw_new_forms_list" class="mw-ui-field" id="mw_new_form_list_title" value=""
               style="width: 200px;margin-right: 10px;"/>
        <button class="mw-ui-btn" onclick="mw_create_new_list_<?php print $rand; ?>()"><?php _e("Create"); ?></button>
        &nbsp;<span class="mw-ui-delete"
                    onclick="mw.$('.mw_create_new_forms_list<?php print $rand; ?>, #form_dropdown_lists').toggle()"><?php _e("Cancel"); ?></span>
    </div>

<?php else: ?>
    <div class="mw_create_new_forms_list<?php print $rand; ?>" style="padding-bottom: 12px;">
        <label class="mw-ui-label"><?php _e("Name of the new list"); ?></label>
        <input type="hidden" name="for_module" value="<?php print $params['for-module'] ?>"/>
        <input type="hidden" name="for_module_id" value="<?php print $params['for-module-id'] ?>"/>
        <input type="text" name="mw_new_forms_list" value="" class="mw-ui-field"
               style="width: 200px;margin-right: 10px;"/>
        <button class="mw-ui-btn" onclick="mw_create_new_list_<?php print $rand; ?>()"><?php _e("Create"); ?></button>
    </div>
<?php endif; ?>

<?php
if (is_admin() == false) {
    return false;
}
$rand = uniqid();
?>

<?php if (!isset($params['for-module']) or !isset($params['for-module-id'])): ?>
    <?php print notif('Error: in module "' . $config['module'] . '" - You must set "for-module" and "for-module-id" parameters on this module', 'error');
    return; ?>
<?php endif; ?>

    <script type="text/javascript">mw.require("forms.js", true);</script>
    <script type="text/javascript">
        isCreating = false;
        function mw_create_new_list() {
            if (!isCreating) {
                isCreating = true;
                mw.form.post('.mw_create_new_list', '<?php print api_link('save_form_list'); ?>', function () {
                        mw.reload_module('<?php print $config['module'] ?>', function () {
                            isCreating = false;
                        });
                    }
                );
            }
            return false;
        }
    </script>

<?php
$selected_list = get_option('list_id', $params['for-module-id']);
$data = get_form_lists('order_by=created_at desc&module_name=' . $params['for-module']);
?>

    <h5 class="form-label font-weight-bold mb-3"><?php _e("Contact form lists"); ?></h5>
<?php if (is_array($data)): ?>
    <div id="form_dropdown_lists">
        <script>
            function browseAllLists() {
                var browseAllLink = '<?php print admin_url('view:') . $params['for-module'] ?>/load_list:' + $('.form-list-id').val();
                window.open(browseAllLink, '_blank');
            }
        </script>

        <div class="row">
            <div class="col-6">
                <label class="form-label"><?php _e("Select list for current form"); ?></label>
                <small class="text-muted d-block mb-2"><?php _e("Select a list in which incoming entries will be saved"); ?></small>
                <select name="list_id" class="mw_option_field form-list-id  form-select" data-width="100%" option-group="<?php print $params['for-module-id'] ?>">
                    <option value="" <?php if (intval($selected_list) == 0): ?>   selected="selected"  <?php endif; ?>><?php _e("Default list"); ?></option>
                    <?php foreach ($data as $item): ?>
                        <option value="<?php print $item['id'] ?>" <?php if ((intval($selected_list) == intval($item['id']))): ?>   selected="selected"  <?php endif; ?>><?php print $item['title'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="mw_create_new_list" style="display: none;">
        <label class="form-label"><?php _e("List name"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Name of the new list"); ?></small>
        <div class="form-inline">
            <div class="form-group mr-2">
                <input type="hidden" name="for_module" value="<?php print $params['for-module'] ?>"/>
                <input type="hidden" name="for_module_id" value="<?php print $params['for-module-id'] ?>"/>
                <input type="text" name="mw_new_forms_list" class="form-control" id="mw_new_form_list_title" value=""/>
            </div>
            <button class="btn btn-primary" onclick="mw_create_new_list()"><?php _e("Create"); ?></button>
            &nbsp;<span class="btn btn-link text-danger" onclick="mw.$('.mw_create_new_list, #form_dropdown_lists').toggle()"><?php _e("Cancel"); ?></span>
        </div>
    </div>

    <div class="my-3">
        <label class="form-label d-block"><?php _e("Manage lists"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e("Go to see your lists or create new one"); ?></small>

        <button class="btn btn-outline-primary btn-sm mr-3" onclick="mw.$('.mw_create_new_list, #form_dropdown_lists').toggle()"><?php _e("Create List"); ?></button>
        <a href="#" onClick="browseAllLists();" target="_blank" class="btn btn-link btn-sm px-0"><?php _e("Browse Lists"); ?></a>
    </div>


<?php else: ?>
    <div class="mw_create_new_list">
        <label class="form-label"><?php _e("Name of the new list"); ?></label>
        <div class="form-inline">
            <div class="form-group mr-1 mb-2">
                <input type="hidden" name="for_module" value="<?php print $params['for-module'] ?>"/>
                <input type="hidden" name="for_module_id" value="<?php print $params['for-module-id'] ?>"/>
                <input type="text" name="mw_new_forms_list" value="" class="form-control"/>
            </div>
            <button class="btn btn-primary mb-2" onclick="mw_create_new_list()"><?php _e("Create"); ?></button>
        </div>
    </div>
<?php endif; ?>

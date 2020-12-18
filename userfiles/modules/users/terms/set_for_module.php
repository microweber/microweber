<?php
if (!user_can_access('module.users.terms.index')) {
    return;
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('.<?php print $config['module_class'] ?>', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>.");
        });
    });
</script>

<?php
$mod_name = 'users';

if (isset($params['for_module'])) {
    $mod_name = $params['for_module'];
}
?>

<script>
    function mw_admin_for_module_module_edit_tos(module_name) {
        var modalTitle = '<?php _e("Terms and Conditions"); ?>';

        mw.dialog({
            content: '<div id="mw_admin_edit_tos_item_module"></div>',
            title: modalTitle,
            id: 'mw_admin_edit_tos_item_popup_modal'
        });

        var params = {}
        params.for_module = module_name;
        mw.load_module('users/terms/edit', '#mw_admin_edit_tos_item_module', null, params);
    }
</script>

<div class="form-group mb-3">
    <div class="custom-control custom-switch pl-0">
        <label class="d-inline-block mr-5" for="require_terms<?php print $params['id']; ?>"><?php _e('No') ?></label>
        <input type="checkbox" parent-reload="true" name="require_terms" id="require_terms<?php print $params['id']; ?>" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field custom-control-input" option-group="<?php print $mod_name ?>"<?php if (get_option('require_terms', $mod_name) == 'y'): ?>checked<?php endif; ?>/>
        <label class="custom-control-label" for="require_terms<?php print $params['id']; ?>"><?php _e('Yes') ?></label>
    </div>
</div>

<?php only_admin_access(); ?>
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

        mw_admin_edit_tos_item_popup_modal_opened = mw.modal({
            content: '<div id="mw_admin_edit_tos_item_module"></div>',
            title: modalTitle,
            id: 'mw_admin_edit_tos_item_popup_modal'
        });

        var params = {}
        params.for_module = module_name;
        mw.load_module('users/terms/edit', '#mw_admin_edit_tos_item_module', null, params);
    }

</script>

<div class="mw-ui-field-holder">
    <label class="mw-ui-check">
        <input type="checkbox" parent-reload="true" name="require_terms" value="y" data-value-unchecked="n"
               data-value-checked="y" class="mw_option_field" option-group="<?php print $mod_name ?>"
            <?php if (get_option('require_terms', $mod_name) == 'y'): ?>   checked="checked"  <?php endif; ?>
        />
        <span></span><span><?php _e("Users must agree to the Terms and Conditions"); ?>     </span> </label>
    <a href="#" onclick="mw_admin_for_module_module_edit_tos('<?php print $mod_name ?>')"
       class="mw-ui-btn mw-ui-btn-small"><span class="mw-icon-pen"></span><?php _e('Edit') ?></a>
</div>

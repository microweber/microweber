<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <?php
        $rand_id = md5(serialize($params));
        $hide_in_live_edit = get_option('hide_in_live_edit', $params['id']);
        if ($hide_in_live_edit != '' OR $hide_in_live_edit != false) {
            $hide_in_live_edit == true;
        }
        ?>

        <div class="module-live-edit-settings module-embed-settings">
            <div id="mw_email_source_code_editor<?php print $rand_id ?>">
                <div class="form-group">
                    <label class="control-label"><?php _e("Insert Embed Code"); ?></label>
                    <textarea name="source_code" class="mw_option_field form-control" rows="10" data-refresh="embed"><?php print get_option('source_code', $params['id']) ?></textarea>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="mw_option_field custom-control-input" name="hide_in_live_edit" id="hide_in_live_edit" value="true" data-refresh="embed" <?php if ($hide_in_live_edit): ?>checked<?php endif; ?>>
                        <label class="custom-control-label" for="hide_in_live_edit"><?php _e("Hide in Live Edit"); ?></label>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

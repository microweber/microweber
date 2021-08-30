<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <div class="module-live-edit-settings module-ants-settings">
            <div class="form-group">
                <label class="control-label"><?php _lang("Number of ants", "modules/ants"); ?></label>
                <div class="range-slider">
                    <input name="number_of_ants" data-refresh="ants" class="mw-ui-field-range mw_option_field mw-full-width" type="range" min="3" max="100" value="<?php print get_option('number_of_ants', $params['id']) ?>">
                </div>
            </div>
        </div>
    </div>
</div>

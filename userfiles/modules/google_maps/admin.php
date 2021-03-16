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
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <script type="text/javascript">
            zoommap = function (val) {
                mw.$("#zoom_level").val(val).trigger("change");
            }
        </script>

        <div class="module-live-edit-settings  module-google-maps-settings">
            <div class="form-group">
                <label class="control-label"><?php _e("Enter Your Address"); ?></label>
                <input name="data-address" class="mw_option_field form-control" id="addr" type="text" value="<?php print get_option('data-address', $params['id']) ?>" placeholder="<?php _e('Example: Bulgaria, Sofia, bul. Cherni Vrah 47'); ?>"/>
            </div>

            <div class="form-group">
                <label class="control-label"><?php _e("Zoom Level"); ?></label>
                <div class="range-slider">
                    <input name="data-zoom" class="mw-ui-field-range mw_option_field mw-full-width" max="21" min="0" type="range" id="zoom_level" value="<?php print get_option('data-zoom', $params['id']) ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>

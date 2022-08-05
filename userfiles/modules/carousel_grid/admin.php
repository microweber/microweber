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
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _lang($module_info['name'], "modules/carousel_grid"); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        $items_number = get_option('items_number', $params['id']);
        $maxRowHeight = get_option('max_row_height', $params['id']);
        $rowHeight = get_option('row_height', $params['id']);
        if (!$maxRowHeight) {
            $maxRowHeight = 250;
        }
        if (!$rowHeight) {
            $rowHeight = 120;
        }
        if (!$items_number) {
            $items_number = 10;
        }
        ?>
        <script>
            $(mwd).ready(function () {
                $('[data-type="pictures/admin"]').on('change', function () {
                    mw.reload_module_everywhere('#<?php print $params['id']; ?>')
                });
                mw.on.moduleReload('pa<?php print $params['id']; ?>', function () {
                    $('[data-type="pictures/admin"]').on('change', function () {
                        mw.reload_module_everywhere('#<?php print $params['id']; ?>')
                    });
                });
            });
        </script>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i><?php _lang("List of Images", "modules/carousel_grid") ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _lang('Settings', "modules/carousel_grid"); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="list">
                <div class="module-live-edit-settings module-carousel-grid-settings" style="padding: 0">
                    <module type="pictures/admin" rel_id="<?php print $params['id']; ?>" id="pa<?php print $params['id']; ?>"/>
                </div>
            </div>

            <div class="tab-pane fade" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-carousel-grid-settings">
                    <div class="form-group">
                        <label class="control-label"><?php _lang('Items per slide', "modules/carousel_grid"); ?></label>
                        <input type="number" class="mw_option_field form-control" name="items_number" value="<?php print $items_number; ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php _lang('Minimum Row height', "modules/carousel_grid"); ?></label>
                        <input type="number" class="mw_option_field form-control" name="row_height" placeholder="120" value="<?php print $rowHeight; ?>"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php _lang('Max Row height', "modules/carousel_grid"); ?></label>
                        <input type="number" class="mw_option_field form-control" name="max_row_height" placeholder="250" value="<?php print $maxRowHeight; ?>"/>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>
        </div>
    </div>
</div>

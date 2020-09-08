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
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <?php
                $columns = get_option('columns', $params['id']);
                $feature = get_option('feature', $params['id']);

                $column_1 = '';
                $column_2 = '';
                $column_3 = '';
                $column_4 = '';

                $feature_1 = '';
                $feature_2 = '';
                $feature_3 = '';
                $feature_4 = '';

                $style_1 = '';
                $style_2 = '';
                $style_3 = '';
                $style_4 = '';
                $style_5 = '';

                if (get_option('columns', $params['id']) == 1) {
                    $column_1 = 'selected';
                } elseif (get_option('columns', $params['id']) == 2) {
                    $column_2 = 'selected';
                } elseif (get_option('columns', $params['id']) == 3) {
                    $column_3 = 'selected';
                } elseif (get_option('columns', $params['id']) == 4) {
                    $column_4 = 'selected';
                }

                if (get_option('feature', $params['id']) == 1) {
                    $feature_1 = 'selected';
                } elseif (get_option('feature', $params['id']) == 2) {
                    $feature_2 = 'selected';
                } elseif (get_option('feature', $params['id']) == 3) {
                    $feature_3 = 'selected';
                } elseif (get_option('feature', $params['id']) == 4) {
                    $feature_4 = 'selected';
                }

                if (get_option('style_color', $params['id']) == 'bg-primary') {
                    $style_1 = 'selected';
                } elseif (get_option('style_color', $params['id']) == 'bg-success') {
                    $style_2 = 'selected';
                } elseif (get_option('style_color', $params['id']) == 'bg-info') {
                    $style_3 = 'selected';
                } elseif (get_option('style_color', $params['id']) == 'bg-warning') {
                    $style_4 = 'selected';
                } elseif (get_option('style_color', $params['id']) == 'bg-danger') {
                    $style_5 = 'selected';
                }
                ?>

                <!-- Settings Content -->
                <div class="module-live-edit-settings module-pricing-table-settings">
                    <div class="form-group">
                        <label class="control-label" for="columns"><?php _e('Columns count'); ?></label>
                        <select name="columns" data-refresh="pricing_table" class="mw_option_field selectpicker" data-width="100%" id="columns">
                            <option value="1" <?php print $column_1; ?>>1</option>
                            <option value="2" <?php print $column_2; ?>>2</option>
                            <option value="3" <?php print $column_3; ?>>3</option>
                            <option value="4" <?php print $column_4; ?>>4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="feature"><?php _e('Feature element'); ?></label>
                        <select name="feature" data-refresh="pricing_table" class="mw_option_field selectpicker" data-width="100%" id="feature">
                            <option value="1" <?php print $feature_1; ?>>1</option>
                            <option value="2" <?php print $feature_2; ?>>2</option>
                            <option value="3" <?php print $feature_3; ?>>3</option>
                            <option value="4" <?php print $feature_4; ?>>4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="style_color"><?php _e('Style color'); ?></label>
                        <select name="style_color" data-refresh="pricing_table" class="mw_option_field selectpicker" data-width="100%" id="style_color">
                            <option value="bg-primary" <?php print $style_1; ?>>Primary</option>
                            <option value="bg-success" <?php print $style_2; ?>>Success</option>
                            <option value="bg-info" <?php print $style_3; ?>>Info</option>
                            <option value="bg-warning" <?php print $style_4; ?>>Warning</option>
                            <option value="bg-danger" <?php print $style_5; ?>>Danger</option>
                        </select>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
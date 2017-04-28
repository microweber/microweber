<?php only_admin_access(); ?>

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
?>

<script type="text/javascript">
    mw.lib.require('bootstrap3ns');

    $(document).ready(function () {
    });
</script>

<div class="module-live-edit-settings">
    <div class="bootstrap3ns">

        <div class="row">
            <div class="col-xs-12">

                <div class="form-group">
                    <div class="row">
                        <label class="col-xs-4 control-label" for="columns"><?php _e('Columns count'); ?></label>
                        <div class="col-xs-8">
                            <select name="columns" data-refresh="pricing_table" class="form-control mw_option_field" id="columns">
                                <option value="1" <?php print $column_1; ?>>1</option>
                                <option value="2" <?php print $column_2; ?>>2</option>
                                <option value="3" <?php print $column_3; ?>>3</option>
                                <option value="4" <?php print $column_4; ?>>4</option>
                            </select>
                        </div>
                    </div>
                </div>

                <br/><br/>

                <div class="form-group">
                    <div class="row">
                        <label class="col-xs-4 control-label" for="feature"><?php _e('Feature'); ?></label>
                        <div class="col-xs-8">
                            <select name="feature" data-refresh="pricing_table" class="form-control mw_option_field" id="feature">
                                <option value="1" <?php print $feature_1; ?>>1</option>
                                <option value="2" <?php print $feature_2; ?>>2</option>
                                <option value="3" <?php print $feature_3; ?>>3</option>
                                <option value="4" <?php print $feature_4; ?>>4</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <br/><br/>

                <module type="admin/modules/templates"/>

            </div>
        </div>

    </div>
</div>
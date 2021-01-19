<?php
must_have_access();
?>

<script type="text/javascript">
    mw.require('forms.js');
    mw.require('options.js');
</script>

<script type="text/javascript">
    $(document).ready(function () {
        mw.options.form('#stats-units-setup', function () {
            mw.notification.success("<?php _ejs("Saved"); ?>");
        });
    });
</script>

<div id="stats-units-setup">
    <div class="form-group">
        <label class="control-label d-block"><?php _e("Enable statistics"); ?></label>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input type="radio" id="stats_disabled1" name="stats_disabled" class="mw_option_field custom-control-input" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_disabled', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_disabled1"><?php _e("Enabled"); ?></label>
        </div>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input type="radio" id="stats_disabled2" name="stats_disabled" class="mw_option_field custom-control-input" data-option-group="site_stats" value="1" type="radio" <?php if (get_option('stats_disabled', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_disabled2"><?php _e("Disabled"); ?></label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label d-block"><?php _e("Tracking settings"); ?></label>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input type="radio" id="stats_is_buffered1" name="stats_is_buffered" class="mw_option_field custom-control-input" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_is_buffered', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_is_buffered1"><?php _e("Live"); ?></label>
        </div>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input type="radio" id="stats_is_buffered2" name="stats_is_buffered" class="mw_option_field custom-control-input" data-option-group="site_stats" value="1" type="radio" <?php if (get_option('stats_is_buffered', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_is_buffered2"><?php _e("Buffered (records every 1 minute)"); ?></label>
        </div>

    </div>



    <div class="form-group">
        <label class="control-label d-block"><?php _e("Views counter"); ?></label>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input type="radio" id="stats_views_counter_live_stats1" name="stats_views_counter_live_stats" class="mw_option_field custom-control-input" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_views_counter_live_stats', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_views_counter_live_stats1"><?php _e("Cached"); ?></label>
        </div>

        <div class="custom-control custom-radio d-inline-block mr-2">
            <input type="radio" id="stats_views_counter_live_stats2" name="stats_views_counter_live_stats" class="mw_option_field custom-control-input" data-option-group="site_stats" value="1" type="radio" <?php if (get_option('stats_views_counter_live_stats', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_views_counter_live_stats2"><?php _e("Live"); ?></label>
        </div>
    </div>



</div>

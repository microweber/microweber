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

    <div class="mb-3">
        <label class="form-label"><?php _e("Enable statistics"); ?></label>
        <small class="text-muted mb-3 d-block"><?php _e("Show or hide statistic module from the dashboard"); ?></small>

        <label class="form-check form-switch" style="width: 100%;">
            <input type="checkbox" id="stats_disabled1" name="stats_disabled" class="mw_option_field form-check-input me-2" data-value-checked="y" data-value-unchecked="n" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_disabled', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
        </label>
    </div>

    <div class="row">
        <label class="form-label d-block"><?php _e("Tracking settings"); ?></label>
        <small class="text-muted mb-3 d-block"><?php _e("Interval to save the statistics in the database"); ?></small>

        <div class="custom-control custom-radio d-inline-block me-2 my-1">
            <input type="radio" id="stats_is_buffered1" name="stats_is_buffered" class="mw_option_field form-check-input" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_is_buffered', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_is_buffered1"><?php _e("Live"); ?></label>

        </div>

        <div class="custom-control custom-radio d-inline-block me-2 my-1">
            <input type="radio" id="stats_is_buffered2" name="stats_is_buffered" class="mw_option_field form-check-input" data-option-group="site_stats" value="1" type="radio" <?php if (get_option('stats_is_buffered', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_is_buffered2"><?php _e("Buffered (records every 1 minute)"); ?></label>
        </div>
    </div>


    <div class="row mt-3">
        <label class="form-label d-block"><?php _e("Views counter"); ?></label>
        <small class="text-muted mb-3 d-block"><?php _e("Live: saves the statistics in real time"); ?></small>

        <div class="custom-control custom-radio d-inline-block me-2 my-1">
            <input type="radio" id="stats_views_counter_live_stats1" name="stats_views_counter_live_stats" class="mw_option_field form-check-input" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_views_counter_live_stats', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_views_counter_live_stats1"><?php _e("Cached"); ?></label>
        </div>

        <div class="custom-control custom-radio d-inline-block me-2 my-1">
            <input type="radio" id="stats_views_counter_live_stats2" name="stats_views_counter_live_stats" class="mw_option_field form-check-input" data-option-group="site_stats" value="1" type="radio" <?php if (get_option('stats_views_counter_live_stats', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <label class="custom-control-label" for="stats_views_counter_live_stats2"><?php _e("Live"); ?></label>
        </div>
    </div>

</div>

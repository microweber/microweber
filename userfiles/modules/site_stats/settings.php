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

<div id="stats-units-setup mb-3">

    <div class="form-check form-switch my-3" style="width: 100%;">
        <label class="form-label d-block">
            <input type="checkbox" id="stats_disabled1" name="stats_disabled" class="mw_option_field form-check-input me-2" data-value-checked="y" data-value-unchecked="n" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_disabled', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <span class="form-check-label"><?php _e("Enable Statistics"); ?></span>
        </label>
    </div>

    <div class="form-check form-switch my-3" style="width: 100%;">
        <label class="form-label d-block">
            <input type="checkbox" id="stats_is_buffered1" name="stats_is_buffered" class="mw_option_field form-check-input me-2" data-value-checked="y" data-value-unchecked="n" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_is_buffered', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <span class="form-check-label"><?php _e("Tracking settings"); ?></span>
        </label>
    </div>


    <div class="form-check form-switch my-3" style="width: 100%;">
        <label class="form-label d-block">
            <input type="checkbox" id="stats_views_counter_live_stats1" name="stats_views_counter_live_stats" class="mw_option_field form-check-input me-2" data-value-checked="y" data-value-unchecked="n" data-option-group="site_stats" value="0" type="radio" <?php if (get_option('stats_views_counter_live_stats', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <span class="form-check-label"><?php _e("Views counter"); ?></span>
        </label>
    </div>


</div>

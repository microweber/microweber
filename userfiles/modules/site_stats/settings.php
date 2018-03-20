<?php
only_admin_access();
?>


<script  type="text/javascript">
    mw.require('forms.js');
    mw.require('options.js');

</script>
<script  type="text/javascript">
    $(document).ready(function(){

        mw.options.form('#stats-units-setup', function(){
            mw.notification.success("<?php _e("Saved"); ?>");
    });
    });
</script>



<div id="stats-units-setup" class="mw-ui-box-content">
	<h2>
		<?php _e("Setup statistics"); ?>
</h2>

<div class="mw-ui-box mw-ui-box-content">

    <label class="mw-ui-label">
        <?php _e("Enable"); ?> <?php _e("statistics"); ?>
    </label>


    <div class="mw-ui-check-selector">
        <label class="mw-ui-check">
            <input name="stats_disabled" class="mw_option_field"  data-option-group="site_stats"  value="0"  type="radio"  <?php if(get_option('stats_disabled', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <span></span><span>
    		<?php _e("Enabled"); ?>
    		</span>
        </label>
        <label class="mw-ui-check">
            <input name="stats_disabled" class="mw_option_field"     data-option-group="site_stats"  value="1"  type="radio"  <?php if(get_option('stats_disabled', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <span></span><span>
    		<?php _e("Disabled"); ?>
    		</span>
        </label>
    </div>

    <hr>


    <label class="mw-ui-label">
        <?php _e("Tracking"); ?> <?php _e("Settings"); ?>
    </label>

    <div class="mw-ui-check-selector">
        <label class="mw-ui-check">
            <input name="stats_is_buffered" class="mw_option_field"    data-option-group="site_stats"  value="0"  type="radio"  <?php if(get_option('stats_is_buffered', 'site_stats') == 0): ?> checked="checked" <?php endif; ?> >
            <span></span><span>
    		Live
    		</span>
        </label>
        <label class="mw-ui-check">
            <input name="stats_is_buffered" class="mw_option_field"     data-option-group="site_stats"  value="1"  type="radio"  <?php if(get_option('stats_is_buffered', 'site_stats') == 1): ?> checked="checked" <?php endif; ?> >
            <span></span><span>
    		Buffered (records every 1 minute)
    		</span>
        </label>
    </div>
</div>
</div>

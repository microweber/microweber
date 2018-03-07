<?php only_admin_access(); ?>

<?php
$type = get_option('type', $params['id']);
$time_delay = get_option('time_delay', $params['id']);
if (!$time_delay) {
    $time_delay = 3000;
}
?>

<div class="module-live-edit-settings">
    <module type="admin/modules/templates" simple="true"/>

    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Type of work"); ?></label>
        <input type="radio" name="type" class="mw_option_field" value="on_time" data-refresh="popup"
               <?php if ($type == 'on_time'): ?>checked<?php endif; ?> > On time - only first time (if accept) with cookies<br>
        <input type="radio" name="type" class="mw_option_field" value="on_click" data-refresh="popup"
               <?php if ($type == 'on_click'): ?>checked<?php endif; ?> > On click<br>
        <input type="radio" name="type" class="mw_option_field" value="on_leave_window" data-refresh="popup"
               <?php if ($type == 'on_leave_window'): ?>checked<?php endif; ?> > On Leave window<br>
    </div>


    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Time delay in ms (only if type of work is On time)"); ?></label>
        <input type="text" name="time_delay" class="mw_option_field" value="<?php print $time_delay; ?>" data-refresh="popup"/>
    </div>
</div>
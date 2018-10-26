<?php only_admin_access(); ?>

<?php
$type = get_option('type', $params['id']);
$time_delay = get_option('time_delay', $params['id']);
if (!$time_delay) {
    $time_delay = 3000;
}
?>

<script>
    function showHideDelay() {
        var selectedType = $('input[name="type"]:checked').val();
        if (selectedType == 'on_time') {
            $('.js-time-delay').show();
        } else {
            $('.js-time-delay').hide();
        }
    }
    $(document).ready(function () {
        showHideDelay();

        $('input[name="type"]').on('change', function () {
            showHideDelay();
        });
    });
</script>

<div class="mw-accordion">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-settings"></i> Settings
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="module-live-edit-settings module-popup-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Show Pop-Up event"); ?></label>

                    <ul class="mw-ui-inline-list">
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_click" data-refresh="popup"
                                       <?php if ($type == 'on_click'): ?>checked<?php endif; ?>><span></span><span>On click</span>
                            </label>
                        </li>

                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_time" data-refresh="popup"
                                       <?php if ($type == 'on_time'): ?>checked<?php endif; ?>><span></span><span>On time (<span class="tip" data-tipposition="top-center" title="Only first time (if accept) with cookies">?</span>)</span>
                            </label>
                        </li>

                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_leave_window" data-refresh="popup"
                                       <?php if ($type == 'on_leave_window'): ?>checked<?php endif; ?>><span></span><span>On Leave window</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="mw-ui-field-holder js-time-delay" style="display: none;">
                    <label class="mw-ui-label"><?php _e("Time delay in MS"); ?></label>
                    <input type="text" name="time_delay" class="mw_option_field mw-ui-field mw-full-width" value="<?php print $time_delay; ?>" data-refresh="popup"/>
                    <small><?php _e("Only if Show Pop-Up Event is On time"); ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> Templates
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates" simple="true"/>
        </div>
    </div>
</div>
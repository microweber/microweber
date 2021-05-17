<?php
$config = calendar_module_get_config();
$all_days = calendar_get_events('group_by_type=1&group_by_date=1');
?>

<script>
    mw.require("<?php  print  modules_url() ?>calendar/calendar_admin.js");
</script>
<script>
    //function reload_calendar_after_save() {
    //    mw.reload_module_everywhere('#<?php //print $params['id'] ?>//');
    //    mw.reload_module_everywhere('calendar/edit_events');
    //    window.parent.$(window.parent.document).trigger('calendar.update');
    //    mw.top().$(window.parent.document).trigger('calendar.update');
    //    alert(32423452)
    //
    //}
</script>

<div class="module-live-edit-settings">
    <label class="control-label"><?php _e("Add your calendar event"); ?></label>
    <small class="text-muted d-block mb-3"><?php _e("After creating the event, you should go to the live edit and drop the Calendar module in your chosen page."); ?></small>
    <div class="mb-3">
        <a href="javascript:editEventId(0)" class="btn btn-primary btn-rounded"><?php print _e('Add new event'); ?></a>
    </div>

    <?php if ($all_days): ?>
        <?php foreach ($all_days as $recurrence_type => $event_dates): ?>
            <?php
            if (!isset($config['recurrence_type'][$recurrence_type])) {
                continue;
            }
            ?>
            <div class="table-responsive">
                <table class="table-1">
                    <thead>
                    <tr>
                        <th align="left" colspan="7">
                            <strong><?php echo $config['recurrence_type'][$recurrence_type]; ?></strong>
                        </th>
                    </tr>

                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Group</th>
                        <th>Start</th>
                        <th>End</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <?php foreach ($event_dates as $day => $events) { ?>
                        <thead>
                        <tr>
                            <th align="left" colspan="7"><strong><?php echo date("d M Y", strtotime($day)); ?></strong></th>
                        </tr>
                        </thead>
                        <?php
                        if ($events) {
                            foreach ($events as $event) {
                                ?>
                                <tr class="js-event-group-id-toggle js-event-group-id-toggle-<?php print($event['calendar_group_name']) ?>">
                                    <td><?php print($event['id']) ?></td>
                                    <td><?php print($event['title']) ?></td>
                                    <td><a href="javascript:$('.js-event-group-id-toggle').toggle();$('.js-event-group-id-toggle-<?php print($event['calendar_group_name']) ?>').toggle();"><?php print($event['calendar_group_name']) ?></a></td>
                                    <td><?php print($event['start_date']) ?></td>
                                    <td><?php print($event['end_date']) ?></td>
                                    <td class="text-center" style="width: 200px;">
                                        <button onclick="editEventId('<?php print($event['id']) ?>')" class="btn btn-outline-primary btn-sm"><?php print _e('Edit'); ?></button>
                                        <button onclick="deleteEvent('<?php print($event['id']) ?>')" class="btn btn-outline-danger btn-sm"><?php print _e('Delete'); ?></button>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

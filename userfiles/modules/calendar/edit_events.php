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

    <div class="mw-ui-field-holder p-t-20 p-b-20 text-right">
        <a href="javascript:editEventId(0)" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded">
            <span class="fas fa-plus-circle"></span> &nbsp;<?php print _e('Add new event'); ?>
        </a>
    </div>


    <?php
    if ($all_days) {
        foreach ($all_days as $recurrence_type => $event_dates) {

            if (!isset($config['recurrence_type'][$recurrence_type])) {

                continue;
            }
            ?>
            <table class="table-style-1 mw-ui-table">
                <thead>
                <tr>
                    <th align="left" colspan="7">
                <span>
                <strong style="font-size:15px;">
                <?php echo $config['recurrence_type'][$recurrence_type]; ?>
                <?php
                //print date("d M Y", strtotime($day));
                ?>
                </strong>
                </span>
                    </th>
                </tr>
                </thead>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Group</th>
                    <th>Start</th>
                    <th>End</th>
                    <th class="center">Actions</th>
                </tr>
                </thead>
                <?php foreach ($event_dates as $day => $events) { ?>
                    <thead>
                    <tr>
                        <th align="left" colspan="7">
	                <span>
	                <?php echo date("d M Y", strtotime($day)); ?>
                        </strong>
	                </span>
                        </th>
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
                                <td class="center" style="width: 200px;">
                                    <button onclick="editEventId('<?php print($event['id']) ?>')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><?php print _e('Edit'); ?></button> &nbsp;
                                    <button onclick="deleteEvent('<?php print($event['id']) ?>')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important mw-ui-btn-outline"><?php print _e('Delete'); ?></button>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </table>
            <br/>
            <?php
        }
    }
    ?>
</div>

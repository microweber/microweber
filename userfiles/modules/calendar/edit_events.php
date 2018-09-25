<?php
$all_days = calendar_get_events('group_by_date=1');

?>

<table class="table-style-1 mw-ui-table">

    <thead>
    <tr>
        <th>id</th>
        <th>title</th>
        <th>group</th>
        <th>start</th>
        <th>end</th>
        <th></th>
        <th></th>

    </tr>
    </thead>
    <?php

    if ($all_days) {
        foreach ($all_days as $day => $events) {
            ?>

            <thead>
            <tr>
                <th align="left" colspan="7"><span><i class="mw-icon-web-calendar"></i> &nbsp;<strong><?php print date("d M Y", strtotime($day)); ?></strong></span></th>

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
                        <td><?php print($event['startdate']) ?></td>
                        <td><?php print($event['enddate']) ?></td>
                        <td class="action-buttons">

                            <button onclick="editEventId('<?php print($event['id']) ?>')"
                                    class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline" title="Edit">
                                Edit
                            </button>



                            <?php // d($event)?>



                        <td class="action-buttons">
                            <button onclick="deleteEvent('<?php print($event['id']) ?>')" class="act act-remove" title="Delete">X</button>

                        </td>
                    </tr>

                    <?php
                }
            } ?>

            <?php
        }
    }
    ?>

</table>

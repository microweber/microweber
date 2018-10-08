<?php
$config = calendar_module_get_config();
$all_days = calendar_get_events('group_by_type=1&group_by_date=1');


?>

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
		        <th></th>
		        <th></th>
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
	                        <td class="action-buttons">
	                            <button onclick="editEventId('<?php print($event['id']) ?>')"
	                                    class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline" title="Edit">
	                                Edit
	                            </button>
	                        <td class="action-buttons">
	                            <button onclick="deleteEvent('<?php print($event['id']) ?>')" class="act act-remove" title="Delete">X</button>
	                        </td>
	                    </tr>
	                    <?php
	                }
	            } 
            }
            ?>
			</table>
			<br />
            <?php
        }
    }
    ?>

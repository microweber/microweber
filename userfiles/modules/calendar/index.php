<?php
/*
 * Prepared by nick@easy-host.uk
 * Credit for CRUD with jQuery and Php: Ashik Basheer https://www.jqueryajaxphp.com/fullcalendar-crud-with-jquery-and-php/
 * Source for tooltip: http://jsfiddle.net/539jx/3/
 *
 * Known issues
 * ============
 *
 * 1) Module is not reloading after live editiing
 *
 * Proposed future updates:
 * ========================
 *
 * 1) Multiple calendar support
 * ----------------------------
 * This module works for one calendar, it would need further development to work with multiple calendars.
 * The content_id would need to be saved in a field in the calender table to link events to the correct calendar.
 * If an instance of the calendar module was deleted then the associated events would also need to be deleted.
 *
 * 2) View configuration
 * ---------------------
 * The different fullcalendar views available could be added to configurable options in admin.
 *
 * 3) Multiple day events
 * ----------------------
 * Add functionality to set multiple day events
 *
 * 4) All day events
 * -----------------
 * Add functionality to set all day events
 *
 */
$template = get_option('data-template', $params['id']);
if ($template == false and isset($params['template'])) {
	$template = $params['template'];
}

$calendar_group_id = get_option('calendar_group_id', $params['id']);

if (! $calendar_group_id) {
	$calendar_group_id = 0;
}

$event_count = mw()->database_manager->get("table=calendar&count=true");

$dayGroups = $all_days = calendar_get_events_groups_api('yearmonth=0');

$save_groups = calendar_get_groups();

$groups = [
	[
		'id' => '0',
		'title' => 'Main event'
	]
];

if ($save_groups) {
	$groups = array_merge($groups, $save_groups);
}


$events = calendar_get_events_api($params);


if (!$event_count) {
	return print lnotif(_e('Click here to edit Calendar', true));
}

$template_file = false;

if ($template != false and strtolower($template) != 'none') {
	$template_file = module_templates($config['module'], $template);
}

if ($template_file == false) {
	$template_file = module_templates($config['module'], 'default');
}

if ($template_file != false and is_file($template_file)) {
	include ($template_file);
}

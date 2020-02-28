<?php
$config = array();
$config['name'] = "Calendar";
$config['author'] = "selfworksbg@gmail.com"; // Bozhidar Slaveykov
$config['ui'] = true; // if set to true, module will be visible in the toolbar
$config['ui_admin'] = true; // if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.3;

$config['tables'] = array(
    "calendar" => array(
        '$id' => "integer",
        'content_id' => "integer",
        'title' => "string",
        'start_date' => "date",
        'end_date' => "date",
        'start_time' => "time",
        'end_time' => "time",
        'description' => "text",
        'short_description' => "text",
        'all_day' => "integer",
        'recurrence_type' => "string",
        'recurrence_repeat_type' => "string",
        'recurrence_repeat_every' => "integer",
        'recurrence_repeat_on' => "text",
        "calendar_group_id" => "integer",
        "image_url" => "string",
        "link_url" => "string",
        "active" => "integer"

    ),
    "calendar_groups" => array(
        '$id' => "integer",
        'title' => "string",
        'settings' => "text"
    )
);

$config['recurrence_type'] = array(
    "doesnt_repeat" => "One time event",
    "daily" => "Daily Event",
    "weekly_on_the_day_name" => "Weekly on the day name",
    "weekly_on_the_days_names" => "Weekly on the days names",
    "weekly_on_all_days" => "Weekly on all days",
    "every_weekday" => "Every weekday",
    "monthly_on_the_day_number" => "Monthly on the day number",
    "monthly_on_the_week_number_day_name" => "Monthly on the week number and day name",
    "annually_on_the_month_name_day_number" => "Monthly on the month name and day number",
    "custom" => "Custom"
);

return $config;

